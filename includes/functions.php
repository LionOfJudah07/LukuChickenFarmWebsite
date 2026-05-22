<?php
/**
 * Generate URL
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

/**
 * Require authentication
 */
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect(url('/public/login.php'));
    }
}

/**
 * Require admin role
 */
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        die('Access denied. Admin privileges required.');
    }
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Verify CSRF token
 */
function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'ETB') {
    return number_format($amount, 2) . ' ' . $currency;
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Get active delivery settings
 */
function getDeliverySettings() {
    $db = Database::getInstance();
    
    $settings = [];
    $result = $db->fetchAll("SELECT setting_key, setting_value FROM delivery_settings");
    
    foreach ($result as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    return $settings;
}

/**
 * Check if delivery is available for order
 */
function isDeliveryAvailable($weight_kg) {
    $settings = getDeliverySettings();
    
    if (!isset($settings['delivery_enabled']) || $settings['delivery_enabled'] !== 'true') {
        return false;
    }
    
    $min_weight = isset($settings['min_weight_kg']) ? intval($settings['min_weight_kg']) : 400;
    return $weight_kg >= $min_weight;
}

/**
 * Calculate delivery fee
 */
function calculateDeliveryFee($weight_kg, $distance_km = 0) {
    $settings = getDeliverySettings();
    
    $base_fee = isset($settings['delivery_fee']) ? floatval($settings['delivery_fee']) : 1500;
    $free_above = isset($settings['free_delivery_above']) ? floatval($settings['free_delivery_above']) : 2000;
    
    // Check if free delivery applies
    if ($free_above > 0 && $weight_kg >= $free_above) {
        return 0;
    }
    
    // Add per km charge if applicable
    $per_km = isset($settings['delivery_fee_per_km']) ? floatval($settings['delivery_fee_per_km']) : 0;
    $distance_charge = $distance_km * $per_km;
    
    return $base_fee + $distance_charge;
}

/**
 * Get page content by name
 */
function getPageContent($page_name) {
    global $current_language;
    $db = Database::getInstance();
    
    $page = $db->fetchOne(
        "SELECT * FROM pages WHERE page_name = ? AND is_published = true",
        [$page_name]
    );
    
    if ($page) {
        $content_field = 'content_' . $current_language;
        $title_field = 'title_' . $current_language;
        
        return [
            'title' => $page[$title_field] ?? $page['title_en'],
            'content' => $page[$content_field] ?? $page['content_en']
        ];
    }
    
    return null;
}

/**
 * Upload image
 */
function uploadImage($file, $category = 'general') {
    $target_dir = UPLOAD_DIR . date('Y/m/');
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return ['error' => 'Invalid file type'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB max
        return ['error' => 'File too large'];
    }
    
    $filename = uniqid() . '.' . $file_extension;
    $filepath = $target_dir . $filename;
    $relative_path = 'uploads/' . date('Y/m/') . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Store in database
        $db = Database::getInstance();
        
        $image_data = [
            'filename' => $filename,
            'original_filename' => $file['name'],
            'filepath' => $relative_path,
            'filesize' => $file['size'],
            'mime_type' => $file['type'],
            'category' => $category,
            'uploaded_by' => $_SESSION['user_id'] ?? null
        ];
        
        $image_id = $db->insert('images', $image_data);
        
        return [
            'success' => true,
            'id' => $image_id,
            'filename' => $filename,
            'path' => $relative_path,
            'url' => url($relative_path)
        ];
    }
    
    return ['error' => 'Failed to upload file'];
}

/**
 * Paginate results
 */
function paginate($current_page, $total_items, $items_per_page = 12) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'total_items' => $total_items,
        'items_per_page' => $items_per_page,
        'offset' => $offset,
        'has_previous' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'previous_page' => $current_page - 1,
        'next_page' => $current_page + 1
    ];
}

/**
 * Generate pagination HTML
 */
function paginationLinks($pagination, $url_pattern) {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($pagination['has_previous']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $pagination['previous_page']) . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }
    
    // Page numbers
    $start = max(1, $pagination['current_page'] - 2);
    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
    
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, 1) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $pagination['current_page']) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    if ($end < $pagination['total_pages']) {
        if ($end < $pagination['total_pages'] - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $pagination['total_pages']) . '">' . $pagination['total_pages'] . '</a></li>';
    }
    
    // Next button
    if ($pagination['has_next']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $pagination['next_page']) . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Get theme preference
 */
function getTheme() {
    return $_COOKIE['theme'] ?? 'light';
}
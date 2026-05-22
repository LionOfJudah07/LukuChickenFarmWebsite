<?php
require_once __DIR__ . '/config.php';

// Initialize language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

$current_language = $_SESSION['lang'];

// Load translations from database
function loadTranslations() {
    static $translations = null;
    
    if ($translations === null) {
        $translations = [];
        try {
            if (class_exists('Database')) {
                $db = Database::getInstance();
                $result = $db->fetchAll("SELECT translation_key, en, am, om FROM translations");
                foreach ($result as $row) {
                    $translations[$row['translation_key']] = [
                        'en' => $row['en'],
                        'am' => $row['am'],
                        'om' => $row['om']
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Translation load error: " . $e->getMessage());
        }
    }
    
    return $translations;
}

/**
 * Translation function with intelligent key matching
 * @param string $key - Translation key or English text
 * @return string - Translated text
 */
function __($key) {
    global $current_language;
    
    static $translations = null;
    if ($translations === null) {
        $translations = loadTranslations();
    }
    
    // If key is empty, return empty
    if (empty($key)) {
        return '';
    }
    
    // Clean the key: lowercase, replace spaces with underscores, remove special chars
    $clean_key = strtolower(trim($key));
    $clean_key = preg_replace('/[^a-z0-9]+/', '_', $clean_key);
    $clean_key = trim($clean_key, '_');
    
    // Common key mappings for phrases that don't match exactly
    $key_mappings = [
        // Home page
        'welcome_to_luku_farm' => 'welcome_to_luku',
        'expertise_begets_quality' => 'slogan',
        'your_trusted_partner_in_poultry_farming_and_feed_supply_since_2010' => 'trusted_partner',
        'our_services' => 'our_services',
        'contact_us' => 'contact_us',
        'about_luku_farm' => 'about_luku_farm',
        'ethiopias_premier_poultry_solutions_provider' => 'premier_poultry',
        'years_of_experience' => 'years_experience',
        'since_2010' => 'since_2010',
        'multiple_branches' => 'multiple_branches',
        'across_addis_ababa' => 'across_addis',
        'loyal_customers' => 'loyal_customers',
        'nationwide' => 'nationwide',
        'feed_delivery' => 'feed_delivery',
        'orders_gt_400_kg' => 'orders_above',
        'orders_over_400_kg' => 'orders_above',
        'orders_400_kg' => 'orders_above',
        'orders_400' => 'orders_above',
        
        // Services
        'comprehensive_poultry_solutions_for_every_need' => 'comprehensive_solutions',
        'view_all_services' => 'view_all',
        'read_more' => 'read_more',
        'learn_more' => 'learn_more',
        
        // Feeds
        'our_feeds' => 'our_feeds',
        'premium_nutrition_for_optimal_poultry_health' => 'premium_nutrition',
        'medicated' => 'medicated_feed',
        
        // Partner
        'official_distributor' => 'official_distributor',
        'jagdish_agro_industry' => 'jagdish_agro',
        'licensed_distributor_of_premium_quality_feeds' => 'licensed_distributor',
        'why_choose_jagdish_agro_feeds' => 'why_jagdish',
        'international_quality_standards' => 'international_quality',
        'scientifically_formulated_nutrition' => 'scientifically_formulated',
        'consistent_quality_batch_after_batch' => 'consistent_quality',
        'trusted_by_leading_farmers' => 'trusted_by_farmers',
        'learn_more_about_our_partner' => 'learn_more',
        
        // CTA
        'ready_to_start_your_poultry_journey' => 'ready_to_start',
        'contact_us_today_for_expert_consultation_and_quality_products' => 'contact_today',
        'find_a_branch' => 'find_branch',
        
        // About page
        'about_us' => 'about_us',
        'our_story' => 'our_story',
        'year_established' => 'year_established',
        'branches' => 'branches_count',
        'happy_customers' => 'customers_count',
        'team_members' => 'team_count',
        'our_mission' => 'our_mission',
        'our_vision' => 'our_vision',
        'our_core_values' => 'core_values',
        'quality' => 'quality_value',
        'integrity' => 'integrity',
        'customer_focus' => 'customer_focus',
        'innovation' => 'innovation',
        'sustainability' => 'sustainability',
        'community' => 'community',
        'quality_is_not_an_act_it_is_a_habit' => 'quality_quote',
        'the_principles_that_guide_everything_we_do' => 'guiding_principles',
        'we_never_compromise_on_quality_from_our_feeds_to_our_services' => 'quality_desc',
        'we_operate_with_honesty_transparency_and_ethical_practices' => 'integrity_desc',
        'our_customers_success_is_our_success' => 'customer_success',
        'we_continuously_improve_and_adopt_modern_farming_techniques' => 'innovation_desc',
        'we_care_for_the_environment_and_promote_sustainable_farming' => 'sustainability_desc',
        'we_support_local_farmers_and_contribute_to_community_development' => 'community_desc',
        
        // Team
        'our_leadership_team' => 'leadership_team',
        'experienced_professionals_dedicated_to_your_success' => 'team_dedication',
        'founder_ceo' => 'founder_ceo',
        'director_of_operations' => 'operations_director',
        'chief_veterinarian' => 'chief_vet',
    ];
    
    // Check if we have a direct match
    if (isset($translations[$clean_key])) {
        $translation = $translations[$clean_key][$current_language] ?? $translations[$clean_key]['en'] ?? '';
        if (!empty($translation)) {
            return $translation;
        }
    }
    
    // Check mapped key
    if (isset($key_mappings[$clean_key])) {
        $mapped_key = $key_mappings[$clean_key];
        if (isset($translations[$mapped_key])) {
            $translation = $translations[$mapped_key][$current_language] ?? $translations[$mapped_key]['en'] ?? '';
            if (!empty($translation)) {
                return $translation;
            }
        }
    }
    
    // Check if the key itself is English text that might be in translations
    foreach ($translations as $t_key => $t_values) {
        if (strcasecmp($t_values['en'], $key) === 0) {
            return $t_values[$current_language] ?? $t_values['en'];
        }
    }
    
    // If in development mode, show missing key
    if (defined('DEV_MODE') && DEV_MODE === true) {
        return '[MISSING: ' . $key . ']';
    }
    
    // Final fallback: return the original key
    return $key;
}

/**
 * Language switcher HTML
 */
function languageSwitcher($class = '') {
    global $current_language;
    
    $languages = [
        'en' => 'English',
        'am' => 'አማርኛ',
        'om' => 'Afaan Oromoo'
    ];
    
    $html = '<div class="dropdown ' . $class . '">';
    $html .= '<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown">';
    $html .= '<i class="fas fa-globe me-1"></i> ' . ($languages[$current_language] ?? 'English');
    $html .= '</button>';
    $html .= '<ul class="dropdown-menu dropdown-menu-end">';
    
    foreach ($languages as $code => $name) {
        $active = ($code === $current_language) ? ' active' : '';
        $html .= '<li><a class="dropdown-item' . $active . '" href="?lang=' . $code . '">' . $name . '</a></li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get localized content from database row
 */
function getLocalizedContent($row, $field) {
    global $current_language;
    
    $field_name = $field . '_' . $current_language;
    
    if (isset($row[$field_name]) && !empty($row[$field_name])) {
        return $row[$field_name];
    }
    
    // Fallback to English
    return $row[$field . '_en'] ?? '';
}

// Handle language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'am', 'om'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $current_language = $_GET['lang'];
    
    // Redirect to remove lang parameter
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirect);
    exit;
}

/**
 * Get current language code
 */
function getCurrentLanguage() {
    global $current_language;
    return $current_language;
}

/**
 * Get language name
 */
function getLanguageName($code = null) {
    $languages = [
        'en' => 'English',
        'am' => 'አማርኛ',
        'om' => 'Afaan Oromoo'
    ];
    
    if ($code === null) {
        global $current_language;
        $code = $current_language;
    }
    
    return $languages[$code] ?? 'English';
}
?>
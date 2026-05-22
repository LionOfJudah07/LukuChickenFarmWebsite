<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Contact Messages';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Mark as read
if ($action === 'read' && $id) {
    $db->update('messages', ['is_read' => true], 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Message marked as read';
    redirect('messages.php');
}

// Mark as unread
if ($action === 'unread' && $id) {
    $db->update('messages', ['is_read' => false], 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Message marked as unread';
    redirect('messages.php');
}

// Delete message
if ($action === 'delete' && $id) {
    try {
        $db->delete('messages', 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Message deleted successfully';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to delete message: ' . $e->getMessage();
    }
    redirect('messages.php');
}

// Get filter
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$query = "SELECT * FROM messages";
$params = [];
$conditions = [];

if ($filter === 'unread') {
    $conditions[] = "is_read = false";
} elseif ($filter === 'read') {
    $conditions[] = "is_read = true";
}

if (!empty($search)) {
    $conditions[] = "(name ILIKE ? OR email ILIKE ? OR subject ILIKE ? OR message ILIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY created_at DESC";

// Get messages
$messages = $db->fetchAll($query, $params);

// Get counts
$total_count = $db->fetchOne("SELECT COUNT(*) as count FROM messages")['count'];
$unread_count = $db->fetchOne("SELECT COUNT(*) as count FROM messages WHERE is_read = false")['count'];

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Contact Messages</h1>
        <div>
            <span class="badge bg-secondary me-2">Total: <?php echo $total_count; ?></span>
            <span class="badge bg-success">Unread: <?php echo $unread_count; ?></span>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a href="?filter=all" class="btn <?php echo $filter === 'all' ? 'btn-success' : 'btn-outline-secondary'; ?>">
                            All Messages
                        </a>
                        <a href="?filter=unread" class="btn <?php echo $filter === 'unread' ? 'btn-success' : 'btn-outline-secondary'; ?>">
                            Unread (<?php echo $unread_count; ?>)
                        </a>
                        <a href="?filter=read" class="btn <?php echo $filter === 'read' ? 'btn-success' : 'btn-outline-secondary'; ?>">
                            Read
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search messages..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Messages List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($messages)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5>No messages found</h5>
                <p class="text-muted"><?php echo $search ? 'Try a different search term' : 'When customers contact you, messages will appear here'; ?></p>
            </div>
            <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($messages as $message): ?>
                <div class="list-group-item p-4 <?php echo !$message['is_read'] ? 'bg-light' : ''; ?>">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <?php echo htmlspecialchars($message['name']); ?>
                                <?php if (!$message['is_read']): ?>
                                <span class="badge bg-success ms-2">New</span>
                                <?php endif; ?>
                            </h5>
                            <div class="text-muted small mb-2">
                                <span><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($message['email']); ?></span>
                                <?php if ($message['phone']): ?>
                                <span class="ms-3"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($message['phone']); ?></span>
                                <?php endif; ?>
                                <span class="ms-3"><i class="fas fa-clock me-1"></i> <?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="btn-group">
                            <?php if ($message['is_read']): ?>
                            <a href="?action=unread&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-warning" title="Mark as unread">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <?php else: ?>
                            <a href="?action=read&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-success" title="Mark as read">
                                <i class="fas fa-envelope-open"></i>
                            </a>
                            <?php endif; ?>
                            <a href="mailto:<?php echo $message['email']; ?>?subject=Re: <?php echo urlencode($message['subject'] ?? 'Your inquiry'); ?>" class="btn btn-sm btn-outline-primary" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    
                    <?php if ($message['subject']): ?>
                    <h6 class="fw-bold mb-2">Subject: <?php echo htmlspecialchars($message['subject']); ?></h6>
                    <?php endif; ?>
                    
                    <div class="bg-white p-3 rounded-3">
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    </div>
                    
                    <?php if ($message['replied_at']): ?>
                    <div class="mt-3 small text-muted">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Replied by <?php echo $message['replied_by']; ?> on <?php echo date('M d, Y H:i', strtotime($message['replied_at'])); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
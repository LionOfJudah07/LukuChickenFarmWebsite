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

// Handle Mark as Read
if ($action === 'read' && $id) {
    $db->update('messages', 
        ['is_read' => true], 
        'id = :id', 
        ['id' => $id]
    );
    $_SESSION['success'] = 'Message marked as read';
    redirect('messages.php' . (isset($_GET['filter']) ? '?filter=' . $_GET['filter'] : ''));
}

// Handle Mark as Unread
if ($action === 'unread' && $id) {
    $db->update('messages', 
        ['is_read' => false], 
        'id = :id', 
        ['id' => $id]
    );
    $_SESSION['success'] = 'Message marked as unread';
    redirect('messages.php' . (isset($_GET['filter']) ? '?filter=' . $_GET['filter'] : ''));
}

// Handle Delete
if ($action === 'delete' && $id) {
    try {
        $db->delete('messages', 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Message deleted successfully';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to delete message: ' . $e->getMessage();
    }
    redirect('messages.php' . (isset($_GET['filter']) ? '?filter=' . $_GET['filter'] : ''));
}

// Handle Reply (mark as replied)
if ($action === 'reply' && $id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reply_message = $_POST['reply_message'] ?? '';
        $send_email = isset($_POST['send_email']) ? true : false;
        
        if (!empty($reply_message)) {
            // Get message details
            $message = $db->fetchOne("SELECT * FROM messages WHERE id = ?", [$id]);
            
            if ($message) {
                // Update message as replied
                $db->update('messages', [
                    'is_replied' => true,
                    'replied_at' => date('Y-m-d H:i:s'),
                    'replied_by' => $_SESSION['user_id']
                ], 'id = :id', ['id' => $id]);
                
                // TODO: Send email if configured
                if ($send_email) {
                    // mail($message['email'], "Re: " . $message['subject'], $reply_message);
                }
                
                $_SESSION['success'] = 'Reply sent successfully';
                redirect('messages.php');
            }
        } else {
            $error = 'Reply message cannot be empty';
        }
    }
    
    // Show reply form
    $message = $db->fetchOne("SELECT * FROM messages WHERE id = ?", [$id]);
}

// Handle Bulk Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action'])) {
    $selected_ids = $_POST['selected_ids'] ?? [];
    $bulk_action = $_POST['bulk_action'];
    
    if (!empty($selected_ids)) {
        $ids = implode(',', array_map('intval', $selected_ids));
        
        try {
            if ($bulk_action === 'mark_read') {
                $db->query("UPDATE messages SET is_read = true WHERE id IN ($ids)");
                $_SESSION['success'] = count($selected_ids) . ' messages marked as read';
            } elseif ($bulk_action === 'mark_unread') {
                $db->query("UPDATE messages SET is_read = false WHERE id IN ($ids)");
                $_SESSION['success'] = count($selected_ids) . ' messages marked as unread';
            } elseif ($bulk_action === 'delete') {
                $db->query("DELETE FROM messages WHERE id IN ($ids)");
                $_SESSION['success'] = count($selected_ids) . ' messages deleted';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Bulk action failed: ' . $e->getMessage();
        }
        
        redirect('messages.php' . (isset($_GET['filter']) ? '?filter=' . $_GET['filter'] : ''));
    } else {
        $_SESSION['error'] = 'No messages selected';
        redirect('messages.php' . (isset($_GET['filter']) ? '?filter=' . $_GET['filter'] : ''));
    }
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
} elseif ($filter === 'replied') {
    $conditions[] = "is_replied = true";
} elseif ($filter === 'unreplied') {
    $conditions[] = "is_replied = false";
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

// Get counts for stats
$total_count = $db->fetchOne("SELECT COUNT(*) as count FROM messages")['count'];
$unread_count = $db->fetchOne("SELECT COUNT(*) as count FROM messages WHERE is_read = false")['count'];
$replied_count = $db->fetchOne("SELECT COUNT(*) as count FROM messages WHERE is_replied = true")['count'];

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Contact Messages</h1>
        <div>
            <span class="badge bg-secondary me-2">Total: <?= $total_count ?></span>
            <span class="badge bg-success me-2">Unread: <?= $unread_count ?></span>
            <span class="badge bg-info">Replied: <?= $replied_count ?></span>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'reply' && isset($message)): ?>
    
    <!-- Reply Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Reply to: <?= htmlspecialchars($message['name']) ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <h6 class="fw-bold mb-2">Sender Details</h6>
                        <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($message['name']) ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
                        <?php if ($message['phone']): ?>
                        <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($message['phone']) ?></p>
                        <?php endif; ?>
                        <p class="mb-0"><strong>Date:</strong> <?= date('M d, Y H:i', strtotime($message['created_at'])) ?></p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <h6 class="fw-bold mb-2">Original Message</h6>
                        <?php if ($message['subject']): ?>
                        <p class="mb-2"><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></p>
                        <?php endif; ?>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    </div>
                </div>
            </div>
            
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Your Reply</label>
                    <textarea name="reply_message" class="form-control" rows="6" placeholder="Type your reply here..." required></textarea>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" name="send_email" class="form-check-input" id="sendEmail" checked>
                    <label class="form-check-label" for="sendEmail">Send as email to customer</label>
                    <small class="d-block text-muted">Uncheck to mark as replied without sending email</small>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Send Reply
                    </button>
                    <a href="messages.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php else: ?>
    
    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="btn-group">
                        <a href="?filter=all" class="btn <?= $filter === 'all' ? 'btn-success' : 'btn-outline-secondary' ?>">
                            All <span class="badge bg-light text-dark ms-1"><?= $total_count ?></span>
                        </a>
                        <a href="?filter=unread" class="btn <?= $filter === 'unread' ? 'btn-success' : 'btn-outline-secondary' ?>">
                            Unread <span class="badge bg-light text-dark ms-1"><?= $unread_count ?></span>
                        </a>
                        <a href="?filter=read" class="btn <?= $filter === 'read' ? 'btn-success' : 'btn-outline-secondary' ?>">
                            Read
                        </a>
                        <a href="?filter=unreplied" class="btn <?= $filter === 'unreplied' ? 'btn-success' : 'btn-outline-secondary' ?>">
                            Unreplied
                        </a>
                        <a href="?filter=replied" class="btn <?= $filter === 'replied' ? 'btn-success' : 'btn-outline-secondary' ?>">
                            Replied
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <form method="GET" class="d-flex">
                        <input type="hidden" name="filter" value="<?= $filter ?>">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search messages..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulk Actions Form -->
    <form method="POST" id="bulkForm">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                            <select name="bulk_action" class="form-select form-select-sm w-auto" required>
                                <option value="">Bulk Actions</option>
                                <option value="mark_read">Mark as Read</option>
                                <option value="mark_unread">Mark as Unread</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary ms-2" onclick="return confirm('Apply bulk action?')">Apply</button>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="text-muted"><?= count($messages) ?> messages found</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($messages)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5>No messages found</h5>
                    <p class="text-muted"><?= $search ? 'Try a different search term' : 'When customers contact you, messages will appear here' ?></p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="40"></th>
                                <th width="60">Status</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr class="<?= !$msg['is_read'] ? 'fw-bold bg-light' : '' ?>">
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $msg['id'] ?>" class="form-check-input message-checkbox">
                                </td>
                                <td>
                                    <?php if (!$msg['is_read']): ?>
                                    <span class="badge bg-success" title="Unread">New</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary" title="Read">Read</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($msg['is_replied']): ?>
                                    <br><span class="badge bg-info mt-1">Replied</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($msg['name']) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($msg['email']) ?></small>
                                    <?php if ($msg['phone']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($msg['phone']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($msg['subject'] ?? '(No subject)') ?>
                                </td>
                                <td>
                                    <?= substr(htmlspecialchars($msg['message']), 0, 50) ?>...
                                </td>
                                <td>
                                    <?= date('M d, Y', strtotime($msg['created_at'])) ?>
                                    <br><small class="text-muted"><?= date('H:i', strtotime($msg['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="?action=reply&id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-primary" title="Reply">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                        
                                        <?php if ($msg['is_read']): ?>
                                        <a href="?action=unread&id=<?= $msg['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-outline-warning" title="Mark as Unread">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <?php else: ?>
                                        <a href="?action=read&id=<?= $msg['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-outline-success" title="Mark as Read">
                                            <i class="fas fa-envelope-open"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <a href="?action=delete&id=<?= $msg['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        
                                        <a href="mailto:<?= $msg['email'] ?>?subject=Re: <?= urlencode($msg['subject'] ?? 'Your inquiry') ?>" class="btn btn-sm btn-outline-secondary" title="Send Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
    
    <?php endif; ?>
</div>

<script>
// Select All functionality
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.message-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Prevent form submission if no action selected
document.getElementById('bulkForm')?.addEventListener('submit', function(e) {
    const action = this.querySelector('select[name="bulk_action"]').value;
    const checked = document.querySelectorAll('.message-checkbox:checked').length;
    
    if (!action) {
        e.preventDefault();
        alert('Please select an action');
    } else if (checked === 0) {
        e.preventDefault();
        alert('Please select at least one message');
    } else if (action === 'delete') {
        return confirm('Delete ' + checked + ' selected messages?');
    }
});
</script>

<?php include 'includes/admin_footer.php'; ?>
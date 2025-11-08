<?php
require_once '../includes/functions.php';
require_once '../includes/Post.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$post = new Post();
$result = $post->getPost($_GET['id']);

if (!$result['success']) {
    header('Location: dashboard.php');
    exit;
}

$blog = $result['data'];

// Check if current user is the author
if (!isAuthenticated() || $_SESSION['user_id'] != $blog['user_id']) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $post->delete($blog['id'], $_SESSION['user_id']);
    
    if ($result['success']) {
        header('Location: dashboard.php?success=' . urlencode('Post deleted successfully'));
        exit;
    } else {
        header('Location: dashboard.php?error=' . urlencode($result['message']));
        exit;
    }
}

$pageTitle = "Delete Post";
require_once '../includes/header.php';
?>

<div class="form-container">
    <div class="form-header">
        <h1>Delete Blog Post</h1>
        <p class="subtitle">Are you sure you want to delete this post?</p>
    </div>

    <div class="alert alert-warning">
        <i class='bx bx-error'></i>
        <div>
            <h4>Warning: This action cannot be undone!</h4>
            <p>You are about to delete the post "<strong><?php echo htmlspecialchars($blog['title']); ?></strong>".</p>
            <p>All content and data associated with this post will be permanently removed.</p>
        </div>
    </div>

    <div class="post-preview">
        <h3>Post Preview:</h3>
        <div class="preview-content">
            <h4><?php echo htmlspecialchars($blog['title']); ?></h4>
            <p class="preview-meta">
                Created: <?php echo $blog['formatted_date']; ?> | 
                Author: <?php echo htmlspecialchars($blog['author_name']); ?>
            </p>
            <p class="preview-excerpt"><?php echo htmlspecialchars(substr(strip_tags($blog['content']), 0, 200) . '...'); ?></p>
        </div>
    </div>

    <form method="POST" class="delete-form">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger btn-large" onclick="return confirm('Are you absolutely sure? This cannot be undone!')">
                <i class='bx bx-trash'></i>
                Yes, Delete Permanently
            </button>
            <a href="view-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
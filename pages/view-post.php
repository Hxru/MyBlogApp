<?php
require_once '../includes/functions.php';
require_once '../includes/Post.php';

if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$post = new Post();
$result = $post->getPost($_GET['id']);

if (!$result['success']) {
    header('Location: ../index.php');
    exit;
}

$blog = $result['data'];
$pageTitle = $blog['title'];
require_once '../includes/header.php';

$is_author = isAuthenticated() && $_SESSION['user_id'] == $blog['user_id'];
?>

<div class="post-detail-container">
    <article class="blog-post-full">
        <header class="post-header">
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
            <div class="post-meta">
                <div class="author-info">
                    <i class='bx bx-user'></i>
                    <span>By <?php echo htmlspecialchars($blog['author_name']); ?></span>
                </div>
                <div class="date-info">
                    <i class='bx bx-calendar'></i>
                    <span>Posted on <?php echo $blog['formatted_date']; ?></span>
                </div>
                <?php if (!empty($blog['formatted_updated'])): ?>
                <div class="date-info">
                    <i class='bx bx-edit'></i>
                    <span>Updated on <?php echo $blog['formatted_updated']; ?></span>
                </div>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="post-content">
            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
        </div>

        <div class="post-actions">
            <a href="../index.php" class="btn btn-outline">
                <i class='bx bx-arrow-back'></i>
                Back to Home
            </a>
            
            <?php if ($is_author): ?>
                <a href="edit-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-secondary">
                    <i class='bx bx-edit'></i>
                    Edit Post
                </a>
                <a href="delete-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-danger" 
                   onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                    <i class='bx bx-trash'></i>
                    Delete Post
                </a>
            <?php endif; ?>
        </div>
    </article>
</div>

<?php include '../includes/footer.php'; ?>
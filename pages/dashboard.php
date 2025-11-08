<?php
require_once '../includes/functions.php';
require_once '../includes/Post.php';

$pageTitle = "Dashboard";
require_once '../includes/header.php';

if (!isAuthenticated()) {
    header('Location: ../login.php');
    exit;
}

$post = new Post();
$userPosts = $post->getUserPosts($_SESSION['user_id']);
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Dashboard</h1>
        <a href="create-blog.php" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Write New Blog
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i class='bx bx-check-circle'></i>
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-edit'></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $userPosts['success'] ? count($userPosts['data']) : 0; ?></h3>
                <p>Total Posts</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-show'></i>
            </div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Total Views</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-heart'></i>
            </div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Total Likes</p>
            </div>
        </div>
    </div>

    <div class="my-posts">
        <h2>My Blog Posts</h2>
        
        <?php if (!$userPosts['success'] || empty($userPosts['data'])): ?>
            <div class="no-posts">
                <i class='bx bx-edit-alt'></i>
                <h3>No blog posts yet</h3>
                <p>Start sharing your stories with the world!</p>
                <a href="create-blog.php" class="btn btn-primary">
                    <i class='bx bx-plus'></i>
                    Write Your First Blog
                </a>
            </div>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($userPosts['data'] as $blog): ?>
                    <div class="post-card">
                        <div class="post-header">
                            <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <div class="post-meta">
                                <span class="date">
                                    <i class='bx bx-calendar'></i>
                                    <?php echo $blog['formatted_date']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="post-excerpt">
                            <p><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                        </div>
                        <div class="post-actions">
                            <a href="view-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-outline">
                                <i class='bx bx-show'></i>
                                View
                            </a>
                            <a href="edit-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-secondary">
                                <i class='bx bx-edit'></i>
                                Edit
                            </a>
                            <a href="delete-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class='bx bx-trash'></i>
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
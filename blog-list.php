<?php
require_once 'includes/functions.php';
require_once 'includes/Post.php';

$pageTitle = "All Blog Posts";
require_once 'includes/header.php';

$post = new Post();
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 12;

$postsResult = $post->getAllPosts($page, $limit);
$posts = $postsResult['success'] ? $postsResult['data']['posts'] : [];
$totalPages = $postsResult['success'] ? $postsResult['data']['pages'] : 1;
$totalPosts = $postsResult['success'] ? $postsResult['data']['total'] : 0;
?>

<div class="blog-list-container">
    <div class="page-header">
        <h1>All Blog Posts</h1>
        <p class="subtitle">Discover stories from our community of writers</p>
        
        <?php if (isAuthenticated()): ?>
            <a href="pages/create-blog.php" class="btn btn-primary">
                <i class='bx bx-plus'></i>
                Write New Blog
            </a>
        <?php endif; ?>
    </div>

    <div class="list-stats">
        <div class="stat-badge">
            <i class='bx bx-edit'></i>
            <?php echo $totalPosts; ?> Total Posts
        </div>
        <div class="stat-badge">
            <i class='bx bx-group'></i>
            Active Community
        </div>
    </div>

    <?php if (empty($posts)): ?>
        <div class="no-posts">
            <i class='bx bx-edit-alt'></i>
            <h3>No blog posts yet</h3>
            <p>Be the first to share your story with the world!</p>
            <?php if (!isAuthenticated()): ?>
                <a href="register.php" class="cta-button">
                    <i class='bx bx-user-plus'></i>
                    Join Our Community
                </a>
            <?php else: ?>
                <a href="pages/create-blog.php" class="cta-button">
                    <i class='bx bx-plus'></i>
                    Write Your First Blog
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="posts-grid large">
            <?php foreach ($posts as $post): ?>
                <article class="blog-card large">
                    <div class="card-header">
                        <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <div class="post-meta">
                            <span class="author">
                                <i class='bx bx-user'></i>
                                <?php echo htmlspecialchars($post['author_name']); ?>
                            </span>
                            <span class="date">
                                <i class='bx bx-calendar'></i>
                                <?php echo $post['formatted_date']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-content">
                        <p class="post-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    </div>
                    <div class="card-actions">
                        <a href="pages/view-post.php?id=<?php echo $post['id']; ?>" class="btn-read">
                            <i class='bx bx-book-reader'></i>
                            Read Full Story
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </a>
                <?php endif; ?>

                <div class="pagination-numbers">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="pagination-number active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="pagination-number"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
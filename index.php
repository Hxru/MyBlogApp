<?php
require_once 'includes/functions.php';
require_once 'includes/Post.php';

$pageTitle = "Home - Share Your Stories";
require_once 'includes/header.php';

$post = new Post();
$postsResult = $post->getAllPosts(1, 6); // Get first 6 posts
$recentPosts = $postsResult['success'] ? $postsResult['data']['posts'] : [];
?>


<div class="homepage"> <!-- ADD THIS LINE -->

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Welcome to <span class="gradient-text">MyBlogApp</span></h1>
        <p class="hero-subtitle">Share your stories, experiences, and ideas with the world. Your voice matters.</p>
        
        <div class="hero-stats">
            <div class="stat">
                <i class='bx bx-edit'></i>
                <div>
                    <span class="stat-number"><?php echo $postsResult['success'] ? $postsResult['data']['total'] : '0'; ?></span>
                    <span class="stat-label">Blog Posts</span>
                </div>
            </div>
            <div class="stat">
                <i class='bx bx-group'></i>
                <div>
                    <span class="stat-number"><?php echo $postsResult['success'] ? count($recentPosts) : '0'; ?></span>
                    <span class="stat-label">Active Writers</span>
                </div>
            </div>
            <div class="stat">
                <i class='bx bx-heart'></i>
                <div>
                    <span class="stat-number">0</span>
                    <span class="stat-label">Community Likes</span>
                </div>
            </div>
        </div>
        
        <div class="hero-actions">
            <?php if (isAuthenticated()): ?>
                <a href="pages/create-blog.php" class="cta-button">
                    <i class='bx bx-plus'></i>
                    Write New Blog
                </a>
                <a href="pages/dashboard.php" class="btn btn-outline">
                    <i class='bx bx-dashboard'></i>
                    My Dashboard
                </a>
            <?php else: ?>
                <a href="register.php" class="cta-button">
                    <i class='bx bx-user-plus'></i>
                    Get Started Free
                </a>
                <a href="login.php" class="btn btn-outline">
                    <i class='bx bx-log-in'></i>
                    Sign In
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-graphic">
        <div class="graphic-element">
            <i class='bx bxs-pencil'></i>
        </div>
    </div>
</section>

<!-- Recent Posts Section -->
<section class="posts-section">
    <div class="section-header">
        <h2>Recent Blog Posts</h2>
        <p>Discover the latest stories from our community</p>
    </div>

    <?php if (empty($recentPosts)): ?>
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
        <div class="posts-grid">
            <?php foreach ($recentPosts as $post): ?>
                <article class="blog-card">
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
                            Read More
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <?php if ($postsResult['data']['total'] > 6): ?>
            <div class="section-footer">
                <a href="blog-list.php" class="btn btn-outline">
                    <i class='bx bx-list-ul'></i>
                    View All Posts
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="section-header">
        <h2>Why Choose MyBlogApp?</h2>
        <p>Everything you need to share your stories</p>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class='bx bx-edit-alt'></i>
            </div>
            <h3>Easy Writing</h3>
            <p>Simple and intuitive editor to focus on your writing</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class='bx bx-share-alt'></i>
            </div>
            <h3>Share Instantly</h3>
            <p>Publish your stories and reach readers worldwide</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class='bx bx-group'></i>
            </div>
            <h3>Community</h3>
            <p>Connect with other writers and readers</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class='bx bx-trending-up'></i>
            </div>
            <h3>Grow Audience</h3>
            <p>Build your readership and share your passion</p>
        </div>
    </div>
</section>

</div> <!-- ADD THIS LINE -->

<?php include 'includes/footer.php'; ?>
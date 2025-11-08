<?php
require_once '../includes/functions.php';
$pageTitle = "Create Blog";
require_once '../includes/header.php';

if (!isAuthenticated()) {
    header('Location: ../login.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Validation
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($content)) {
        $errors[] = "Content is required";
    }
    if (strlen($title) < 5) {
        $errors[] = "Title should be at least 5 characters long";
    }
    if (strlen($content) < 50) {
        $errors[] = "Content should be at least 50 characters long";
    }
    
    if (empty($errors)) {
        require_once '../includes/Post.php';
        $post = new Post();
        $result = $post->create($_SESSION['user_id'], $title, $content);
        
        if ($result['success']) {
            $success = true;
            // Clear form
            $title = $content = '';
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="create-blog-page">
    <div class="create-blog-card">
        <div class="create-blog-header">
            <h1>Create New Blog Post</h1>
            <p>Share your story with the world</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class='bx bx-check-circle'></i>
                <div>
                    <h4>Success!</h4>
                    <p>Your blog post has been published successfully!</p>
                    <div class="alert-actions">
                        <a href="dashboard.php" class="btn btn-outline">View My Posts</a>
                        <a href="create-blog.php" class="btn btn-primary">Write Another</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class='bx bx-error-circle'></i>
                <div>
                    <h4>Please fix the following errors:</h4>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" class="blog-form">
            <div class="blog-form-group">
                <label class="blog-form-label">Blog Title</label>
                <input 
                    type="text" 
                    name="title" 
                    class="blog-title-input"
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                    placeholder="Enter a catchy title for your blog post"
                    required
                >
            </div>

            <div class="blog-form-group">
                <label class="blog-form-label">Write your blog post</label>
                <textarea 
                    name="content" 
                    class="blog-content-textarea"
                    placeholder="Write your blog post content here... Tell your story!"
                    required
                ><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
            </div>

            <div class="blog-form-actions">
                <a href="dashboard.php" class="btn-cancel">
                    <i class='bx bx-x'></i>
                    Cancel
                </a>
                <button type="submit" class="btn-publish">
                    <i class='bx bx-paper-plane'></i>
                    Publish Post
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
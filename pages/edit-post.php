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

$pageTitle = "Edit: " . $blog['title'];
require_once '../includes/header.php';

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
    
    if (empty($errors)) {
        $result = $post->update($blog['id'], $_SESSION['user_id'], $title, $content);
        
        if ($result['success']) {
            $success = true;
            // Refresh post data
            $result = $post->getPost($blog['id']);
            $blog = $result['data'];
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="edit-blog-page">
    <div class="edit-blog-card">
        <div class="edit-blog-header">
            <h1>Edit Blog Post</h1>
            <p>Update your story</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class='bx bx-check-circle'></i>
                <div>
                    <h4>Success!</h4>
                    <p>Your blog post has been updated successfully!</p>
                    <div class="alert-actions">
                        <a href="view-post.php?id=<?php echo $blog['id']; ?>" class="btn btn-outline">View Post</a>
                        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
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

        <form method="POST" class="edit-blog-form">
            <div class="edit-form-group">
                <label class="edit-form-label">Blog Title</label>
                <input 
                    type="text" 
                    name="title" 
                    class="edit-title-input"
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : htmlspecialchars($blog['title']); ?>"
                    placeholder="Enter a catchy title for your blog post"
                    required
                >
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Content</label>
                <textarea 
                    name="content" 
                    class="edit-content-textarea"
                    placeholder="Write your blog post content here..."
                    required
                ><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : htmlspecialchars($blog['content']); ?></textarea>
                <div class="edit-char-count">Characters: <span id="charCount">0</span></div>
            </div>

            <div class="edit-form-actions">
                <a href="view-post.php?id=<?php echo $blog['id']; ?>" class="btn-cancel-edit">
                    <i class='bx bx-x'></i>
                    Cancel
                </a>
                <button type="submit" class="btn-update">
                    <i class='bx bx-refresh'></i>
                    Update Post
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
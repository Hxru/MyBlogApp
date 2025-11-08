<?php
require_once 'includes/functions.php';

// Redirect if already logged in
if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$pageTitle = "Create Account";
require_once 'includes/header.php';

$errors = [];
$formData = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $formData = ['username' => $username, 'email' => $email];
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($errors)) {
        require_once 'includes/Auth.php';
        $auth = new Auth();
        $result = $auth->register($username, $email, $password);
        
        if ($result['success']) {
            $_SESSION['user_id'] = $result['data']['id'];
            $_SESSION['username'] = $result['data']['username'];
            $_SESSION['email'] = $result['data']['email'];
            
            header('Location: pages/dashboard.php?success=' . urlencode('Welcome to MyBlogApp! Start writing your first blog post.'));
            exit;
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Join Our Community</h1>
            <p>Create your account and start sharing your stories</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class='bx bx-error-circle'></i>
                <div>
                    <h4>Registration failed</h4>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <i class='bx bx-user'></i>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="<?php echo htmlspecialchars($formData['username']); ?>"
                        placeholder="Choose a username"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class='bx bx-envelope'></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($formData['email']); ?>"
                        placeholder="Enter your email"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class='bx bx-lock-alt'></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Create a password (min. 6 characters)"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-with-icon">
                    <i class='bx bx-lock-alt'></i>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Confirm your password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-large btn-full">
                <i class='bx bx-user-plus'></i>
                Create Account
            </button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
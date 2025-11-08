<?php
require_once 'includes/functions.php';

// Redirect if already logged in
if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $errors[] = "Please enter both username and password";
    } else {
        require_once 'includes/Auth.php';
        $auth = new Auth();
        $result = $auth->login($username, $password);
        
        if ($result['success']) {
            $_SESSION['user_id'] = $result['data']['id'];
            $_SESSION['username'] = $result['data']['username'];
            $_SESSION['email'] = $result['data']['email'];
            
            header('Location: index.php');
            exit;
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyBlogApp</title>
    <link rel="stylesheet" href="assets/css/login-style.css?v=<?php echo time(); ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <!-- Brand Section -->
        <section class="brand-section">
            <div class="brand-content">
                <div class="logo">
                    <i class='bx bxs-pencil'></i>
                    <span>MyBlogApp</span>
                </div>
                
                <h1>Welcome Back!</h1>
                <p class="brand-text">Join our community of writers and share your stories with the world.</p>
                
                <div class="features">
                    <div class="feature">
                        <i class='bx bx-edit-alt'></i>
                        <span>Write and publish your stories</span>
                    </div>
                    <div class="feature">
                        <i class='bx bx-group'></i>
                        <span>Connect with other writers</span>
                    </div>
                    <div class="feature">
                        <i class='bx bx-trending-up'></i>
                        <span>Grow your audience</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Form Section -->
        <section class="form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Welcome back! Please sign in to your account.</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin: 0.5rem 0 0 1rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="">
                    <!-- Username/Email Field -->
                    <div class="input-group">
                        <label for="username">
                            <i class='bx bx-user'></i>
                            Username or Email
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Enter your username or email"
                            value="<?php echo htmlspecialchars($username); ?>"
                            required
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="input-group">
                        <label for="password">
                            <i class='bx bx-lock-alt'></i>
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class='bx bx-hide'></i>
                        </button>
                    </div>

                    <!-- Form Options -->
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="forgot-password.php" class="forgot-password">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <i class='bx bx-log-in'></i>
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                <div class="register-link">
                    Don't have an account? 
                    <a href="register.php" class="register-cta">
                        Create one here
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
            </div>
        </section>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bx bx-show';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bx bx-hide';
            }
        }
    </script>
</body>
</html>
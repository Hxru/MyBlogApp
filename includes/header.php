<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/MyBlogApp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - MyBlogApp' : 'MyBlogApp'; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <header class="app-header">
            <div class="header-content">
                <a href="<?php echo $base_url; ?>/index.php" class="logo">
                    <i class='bx bxs-pencil'></i>
                    <span>MyBlogApp</span>
                </a>
                
                <nav class="nav-menu">
                    <a href="<?php echo $base_url; ?>/index.php" class="nav-link">
                        <i class='bx bx-home'></i>
                        Home
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo $base_url; ?>/pages/dashboard.php" class="nav-link">
                            <i class='bx bx-dashboard'></i>
                            Dashboard
                        </a>
                        <a href="<?php echo $base_url; ?>/pages/create-blog.php" class="nav-link btn-write">
                            <i class='bx bx-edit'></i>
                            Write Blog
                        </a>
                        <div class="user-menu">
                            <div class="user-info">
                                <i class='bx bx-user-circle'></i>
                                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                            </div>
                            <a href="<?php echo $base_url; ?>/pages/logout.php" class="logout-btn">
                                <i class='bx bx-log-out'></i>
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>/login.php" class="nav-link">
                            <i class='bx bx-log-in'></i>
                            Login
                        </a>
                        <a href="<?php echo $base_url; ?>/register.php" class="nav-link btn-primary">
                            <i class='bx bx-user-plus'></i>
                            Sign Up
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="main-content">
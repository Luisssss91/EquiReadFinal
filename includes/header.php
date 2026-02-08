<?php
// Check if user is logged in
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$accessibility_profile = isset($_SESSION['accessibility_profile']) ? $_SESSION['accessibility_profile'] : 'normal';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EquiRead Digital Library</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $accessibility_profile; ?>">
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><i class="fas fa-book-heart"></i> EquiRead Digital Library</h1>
                </div>
                
                <!-- Accessibility Controls -->
                <div class="accessibility-controls">
                    <button id="highContrastToggle" class="accessibility-btn" title="Toggle High Contrast">
                        <i class="fas fa-adjust"></i> High Contrast
                    </button>
                    <button id="increaseFont" class="accessibility-btn" title="Increase Font Size">
                        <i class="fas fa-text-height"></i> A+
                    </button>
                    <button id="decreaseFont" class="accessibility-btn" title="Decrease Font Size">
                        <i class="fas fa-text-height"></i> A-
                    </button>
                    <button id="resetFont" class="accessibility-btn" title="Reset Font Size">
                        <i class="fas fa-text-height"></i> A
                    </button>
                    <button id="textToSpeech" class="accessibility-btn" title="Text to Speech">
                        <i class="fas fa-volume-up"></i> Read
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="resources.php"><i class="fas fa-book-open"></i> Resources</a></li>
                        <?php if($is_logged_in): ?>
                            <?php if($user_role === 'admin'): ?>
                                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <?php endif; ?>
                            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
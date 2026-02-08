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
</head>
<body class="<?php echo $accessibility_profile; ?>">
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>EquiRead Digital Library</h1>
                </div>
                
                <!-- Accessibility Controls -->
                <div class="accessibility-controls">
                    <button id="highContrastToggle" class="accessibility-btn" title="Toggle High Contrast">
                        <span class="icon">⚫⚪</span> High Contrast
                    </button>
                    <button id="increaseFont" class="accessibility-btn" title="Increase Font Size">
                        <span class="icon">A+</span>
                    </button>
                    <button id="decreaseFont" class="accessibility-btn" title="Decrease Font Size">
                        <span class="icon">A-</span>
                    </button>
                    <button id="resetFont" class="accessibility-btn" title="Reset Font Size">
                        <span class="icon">A</span>
                    </button>
                    <button id="textToSpeech" class="accessibility-btn" title="Text to Speech">
                        <span class="icon">🔊</span> Read
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="resources.php">Resources</a></li>
                        <?php if($is_logged_in): ?>
                            <?php if($user_role === 'admin'): ?>
                                <li><a href="dashboard.php">Admin Dashboard</a></li>
                            <?php endif; ?>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
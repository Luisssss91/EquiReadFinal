<?php
include 'config.php';

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user data
$sql = "SELECT * FROM tbl_users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Get user's download history
$downloads_sql = "SELECT d.download_date, r.title, r.file_type, r.resource_id 
                  FROM tbl_downloads d 
                  JOIN tbl_resources r ON d.resource_id = r.resource_id 
                  WHERE d.user_id = ? 
                  ORDER BY d.download_date DESC 
                  LIMIT 5";
$downloads_stmt = mysqli_prepare($conn, $downloads_sql);
mysqli_stmt_bind_param($downloads_stmt, "i", $user_id);
mysqli_stmt_execute($downloads_stmt);
$downloads = mysqli_stmt_get_result($downloads_stmt);

// Handle profile update
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $full_name = trim($_POST["full_name"]);
    $bio = trim($_POST["bio"]);
    $accessibility_profile = trim($_POST["accessibility_profile"]);
    
    $update_sql = "UPDATE tbl_users SET full_name = ?, bio = ?, accessibility_profile = ? WHERE user_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sssi", $full_name, $bio, $accessibility_profile, $user_id);
    
    if(mysqli_stmt_execute($update_stmt)){
        $_SESSION['full_name'] = $full_name;
        $_SESSION['accessibility_profile'] = $accessibility_profile;
        $success_message = "Profile updated successfully!";
        
        // Refresh user data
        $user['full_name'] = $full_name;
        $user['bio'] = $bio;
        $user['accessibility_profile'] = $accessibility_profile;
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-badge">
                    <i class="fas fa-crown"></i> <?php echo ucfirst($user['role']); ?>
                </div>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="profile-join-date">Member since <?php echo date('F Y', strtotime($user['join_date'])); ?></p>
                <?php if(!empty($user['bio'])): ?>
                    <p class="profile-bio"><?php echo htmlspecialchars($user['bio']); ?></p>
                <?php endif; ?>
            </div>
            <div class="profile-stats">
                <div class="stat">
                    <span class="number">15</span>
                    <span class="label">Downloads</span>
                </div>
                <div class="stat">
                    <span class="number">8</span>
                    <span class="label">Favorites</span>
                </div>
                <div class="stat">
                    <span class="number">3</span>
                    <span class="label">Contributions</span>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="sidebar-card">
                    <h3><i class="fas fa-cog"></i> Quick Settings</h3>
                    <div class="settings-list">
                        <a href="#edit-profile" class="setting-item" data-tab="edit-profile">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="#accessibility" class="setting-item" data-tab="accessibility">
                            <i class="fas fa-universal-access"></i> Accessibility
                        </a>
                        <a href="#downloads" class="setting-item" data-tab="downloads">
                            <i class="fas fa-download"></i> Download History
                        </a>
                        <a href="#security" class="setting-item" data-tab="security">
                            <i class="fas fa-shield-alt"></i> Security
                        </a>
                    </div>
                </div>
                
                <div class="sidebar-card">
                    <h3><i class="fas fa-heart"></i> Your Preferences</h3>
                    <div class="preferences">
                        <div class="preference-item">
                            <span>Current Theme:</span>
                            <strong>Girly Pink</strong>
                        </div>
                        <div class="preference-item">
                            <span>Font Size:</span>
                            <strong>Medium</strong>
                        </div>
                        <div class="preference-item">
                            <span>Accessibility:</span>
                            <strong><?php echo ucfirst(str_replace('-', ' ', $user['accessibility_profile'])); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-main">
                <!-- Edit Profile Tab -->
                <div class="tab-content active" id="edit-profile">
                    <div class="tab-header">
                        <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
                        <p>Update your personal information and preferences</p>
                    </div>
                    
                    <?php if(isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($error_message)): ?>
                        <div class="alert alert-error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                <small>Email cannot be changed</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" class="form-control" rows="4" 
                                      placeholder="Tell us a little about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="accessibility_profile">Accessibility Profile</label>
                            <select id="accessibility_profile" name="accessibility_profile" class="form-control">
                                <option value="normal" <?php echo ($user['accessibility_profile'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                                <option value="high-contrast" <?php echo ($user['accessibility_profile'] == 'high-contrast') ? 'selected' : ''; ?>>High Contrast</option>
                                <option value="large-text" <?php echo ($user['accessibility_profile'] == 'large-text') ? 'selected' : ''; ?>>Large Text</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>

                <!-- Download History Tab -->
                <div class="tab-content" id="downloads">
                    <div class="tab-header">
                        <h2><i class="fas fa-download"></i> Download History</h2>
                        <p>Your recently downloaded resources</p>
                    </div>
                    
                    <?php if(mysqli_num_rows($downloads) > 0): ?>
                        <div class="downloads-list">
                            <?php while($download = mysqli_fetch_assoc($downloads)): ?>
                            <div class="download-item">
                                <div class="download-icon">
                                    <i class="fas fa-file-<?php echo getFileTypeIcon($download['file_type']); ?>"></i>
                                </div>
                                <div class="download-details">
                                    <h4><?php echo htmlspecialchars($download['title']); ?></h4>
                                    <p>Downloaded on <?php echo date('M j, Y g:i A', strtotime($download['download_date'])); ?></p>
                                </div>
                                <div class="download-actions">
                                    <a href="resource_view.php?id=<?php echo $download['resource_id']; ?>" class="btn btn-small">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-download fa-3x"></i>
                            <h3>No downloads yet</h3>
                            <p>Start exploring our resources to build your library!</p>
                            <a href="resources.php" class="btn btn-primary">Browse Resources</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.setting-item');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
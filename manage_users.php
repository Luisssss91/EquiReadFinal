<?php
include 'config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Handle user actions
if(isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    if($_GET['action'] == 'delete') {
        // Prevent admin from deleting themselves
        if($user_id != $_SESSION['user_id']) {
            $delete_sql = "DELETE FROM tbl_users WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $delete_sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            if(mysqli_stmt_affected_rows($stmt) > 0) {
                $success_message = "User deleted successfully!";
            } else {
                $error_message = "Error deleting user.";
            }
        } else {
            $error_message = "You cannot delete your own account!";
        }
    } elseif($_GET['action'] == 'toggle_role') {
        $current_role_sql = "SELECT role FROM tbl_users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $current_role_sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        $new_role = ($user['role'] == 'admin') ? 'user' : 'admin';
        
        $update_sql = "UPDATE tbl_users SET role = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id);
        mysqli_stmt_execute($stmt);
        
        $success_message = "User role updated successfully!";
    }
}

// Get all users
$users_sql = "SELECT user_id, full_name, email, role, accessibility_profile, join_date, last_login 
              FROM tbl_users 
              ORDER BY join_date DESC";
$users_result = mysqli_query($conn, $users_sql);
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-users-cog"></i> Manage Users</h1>
            <p>Manage user accounts and permissions</p>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="management-card">
            <div class="card-header">
                <h2><i class="fas fa-user-friends"></i> User List</h2>
                <div class="header-actions">
                    <span class="total-count">Total: <?php echo mysqli_num_rows($users_result); ?> users</span>
                </div>
            </div>

            <div class="card-content">
                <?php if(mysqli_num_rows($users_result) > 0): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Accessibility</th>
                                <th>Join Date</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-small">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <?php echo htmlspecialchars($user['full_name']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo $user['role']; ?>">
                                        <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'crown' : 'user'; ?>"></i>
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="accessibility-badge">
                                        <?php echo ucfirst(str_replace('-', ' ', $user['accessibility_profile'])); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['join_date'])); ?></td>
                                <td>
                                    <?php if($user['last_login']): ?>
                                        <?php echo date('M j, Y', strtotime($user['last_login'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if($user['user_id'] != $_SESSION['user_id']): ?>
                                            <a href="manage_users.php?action=toggle_role&id=<?php echo $user['user_id']; ?>" 
                                               class="btn btn-small btn-warning" 
                                               title="Toggle Role">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                            <a href="manage_users.php?action=delete&id=<?php echo $user['user_id']; ?>" 
                                               class="btn btn-small btn-danger" 
                                               title="Delete User"
                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="btn btn-small btn-disabled" title="Current User">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-users fa-3x"></i>
                        <h3>No Users Found</h3>
                        <p>There are no users in the system yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="management-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Users</h3>
                    <p class="stat-number"><?php echo mysqli_num_rows($users_result); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-info">
                    <h3>Admins</h3>
                    <p class="stat-number">
                        <?php
                        $admin_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_users WHERE role = 'admin'"))['count'];
                        echo $admin_count;
                        ?>
                    </p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-info">
                    <h3>Regular Users</h3>
                    <p class="stat-number">
                        <?php
                        $user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_users WHERE role = 'user'"))['count'];
                        echo $user_count;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
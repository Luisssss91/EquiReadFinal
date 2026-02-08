<?php
include 'config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Handle resource actions
if(isset($_GET['action']) && isset($_GET['id'])) {
    $resource_id = intval($_GET['id']);
    
    if($_GET['action'] == 'delete') {
        // Get file path before deletion
        $file_sql = "SELECT file_path FROM tbl_resources WHERE resource_id = ?";
        $stmt = mysqli_prepare($conn, $file_sql);
        mysqli_stmt_bind_param($stmt, "i", $resource_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resource = mysqli_fetch_assoc($result);
        
        // Delete from database
        $delete_sql = "DELETE FROM tbl_resources WHERE resource_id = ?";
        $stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt, "i", $resource_id);
        mysqli_stmt_execute($stmt);
        
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Delete actual file if it exists
            if($resource['file_path'] && file_exists($resource['file_path'])) {
                unlink($resource['file_path']);
            }
            $success_message = "Resource deleted successfully!";
        } else {
            $error_message = "Error deleting resource.";
        }
    } elseif($_GET['action'] == 'toggle_featured') {
        $current_sql = "SELECT is_featured FROM tbl_resources WHERE resource_id = ?";
        $stmt = mysqli_prepare($conn, $current_sql);
        mysqli_stmt_bind_param($stmt, "i", $resource_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resource = mysqli_fetch_assoc($result);
        
        $new_status = $resource['is_featured'] ? 0 : 1;
        
        $update_sql = "UPDATE tbl_resources SET is_featured = ? WHERE resource_id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ii", $new_status, $resource_id);
        mysqli_stmt_execute($stmt);
        
        $action = $new_status ? 'featured' : 'unfeatured';
        $success_message = "Resource {$action} successfully!";
    }
}

// Get all resources with uploader info
$resources_sql = "SELECT r.*, u.full_name as uploader_name, c.name as category_name 
                  FROM tbl_resources r 
                  LEFT JOIN tbl_users u ON r.uploaded_by = u.user_id 
                  LEFT JOIN tbl_categories c ON r.category = c.name 
                  ORDER BY r.upload_date DESC";
$resources_result = mysqli_query($conn, $resources_sql);
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-book"></i> Manage Resources</h1>
            <p>Manage educational resources in the library</p>
            <div class="header-actions">
                <a href="add_resource.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add New Resource
                </a>
            </div>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="management-card">
            <div class="card-header">
                <h2><i class="fas fa-file-alt"></i> Resource List</h2>
                <div class="header-actions">
                    <span class="total-count">Total: <?php echo mysqli_num_rows($resources_result); ?> resources</span>
                </div>
            </div>

            <div class="card-content">
                <?php if(mysqli_num_rows($resources_result) > 0): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Uploader</th>
                                <th>Downloads</th>
                                <th>Upload Date</th>
                                <th>Featured</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($resource = mysqli_fetch_assoc($resources_result)): ?>
                            <tr>
                                <td><?php echo $resource['resource_id']; ?></td>
                                <td>
                                    <div class="resource-cell">
                                        <div class="resource-icon-small">
                                            <i class="fas fa-file-<?php echo getFileTypeIcon($resource['file_type']); ?>"></i>
                                        </div>
                                        <div class="resource-title">
                                            <strong><?php echo htmlspecialchars($resource['title']); ?></strong>
                                            <small><?php echo substr($resource['description'], 0, 50) . '...'; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge"><?php echo strtoupper($resource['file_type']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($resource['category_name'] ?? 'General'); ?></td>
                                <td><?php echo htmlspecialchars($resource['uploader_name'] ?? 'System'); ?></td>
                                <td>
                                    <span class="download-count"><?php echo $resource['downloads_count']; ?></span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($resource['upload_date'])); ?></td>
                                <td>
                                    <?php if($resource['is_featured']): ?>
                                        <span class="featured-indicator active"><i class="fas fa-star"></i></span>
                                    <?php else: ?>
                                        <span class="featured-indicator"><i class="far fa-star"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="resource_view.php?id=<?php echo $resource['resource_id']; ?>" 
                                           class="btn btn-small btn-info" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="add_resource.php?edit=<?php echo $resource['resource_id']; ?>" 
                                           class="btn btn-small btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="manage_resources.php?action=toggle_featured&id=<?php echo $resource['resource_id']; ?>" 
                                           class="btn btn-small btn-secondary" 
                                           title="<?php echo $resource['is_featured'] ? 'Unfeature' : 'Feature'; ?>">
                                            <i class="fas fa-star"></i>
                                        </a>
                                        <a href="manage_resources.php?action=delete&id=<?php echo $resource['resource_id']; ?>" 
                                           class="btn btn-small btn-danger" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this resource?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-book fa-3x"></i>
                        <h3>No Resources Found</h3>
                        <p>There are no resources in the library yet.</p>
                        <a href="add_resource.php" class="btn btn-primary">Add First Resource</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="management-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Resources</h3>
                    <p class="stat-number"><?php echo mysqli_num_rows($resources_result); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    <h3>Featured</h3>
                    <p class="stat-number">
                        <?php
                        $featured_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_resources WHERE is_featured = 1"))['count'];
                        echo $featured_count;
                        ?>
                    </p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Downloads</h3>
                    <p class="stat-number">
                        <?php
                        $total_downloads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(downloads_count) as total FROM tbl_resources"))['total'];
                        echo $total_downloads ?: '0';
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function getFileTypeIcon($file_type) {
    switch($file_type) {
        case 'pdf': return 'pdf';
        case 'doc': case 'docx': return 'word';
        case 'xls': case 'xlsx': return 'excel';
        case 'ppt': case 'pptx': return 'powerpoint';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image';
        case 'mp4': case 'avi': case 'mov': return 'video';
        case 'mp3': case 'wav': return 'audio';
        default: return 'alt';
    }
}
?>

<?php include 'includes/footer.php'; ?>
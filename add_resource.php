<?php
include 'config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Set higher limits for file uploads
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

$edit_mode = false;
$resource = null;

// Check if we're editing an existing resource
if(isset($_GET['edit'])) {
    $resource_id = intval($_GET['edit']);
    $sql = "SELECT * FROM tbl_resources WHERE resource_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $resource_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resource = mysqli_fetch_assoc($result);
    
    if($resource) {
        $edit_mode = true;
    }
}

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely get form data with null coalescing operator to prevent undefined key warnings
    $title = trim($_POST["title"] ?? '');
    $description = trim($_POST["description"] ?? '');
    $file_type = trim($_POST["file_type"] ?? '');
    $category = trim($_POST["category"] ?? '');
    $tags = trim($_POST["tags"] ?? '');
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;
    
    $upload_success = true;
    $file_path = $resource['file_path'] ?? '';
    $file_size = $resource['file_size'] ?? 0;
    
    // Check if file upload was attempted but failed due to size
    if(isset($_FILES["resource_file"]) && $_FILES["resource_file"]["error"] == UPLOAD_ERR_INI_SIZE) {
        $upload_success = false;
        $max_size = ini_get('upload_max_filesize');
        $error_message = "File is too large. Maximum allowed size is {$max_size}. Please upload a smaller file or contact your administrator to increase the upload limit.";
    }
    // Handle file upload if no previous errors
    elseif($upload_success && isset($_FILES["resource_file"]) && $_FILES["resource_file"]["error"] == UPLOAD_ERR_OK) {
        $allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'mp3', 'wav'];
        $file_extension = strtolower(pathinfo($_FILES["resource_file"]["name"], PATHINFO_EXTENSION));
        
        if(in_array($file_extension, $allowed_types)) {
            $upload_dir = "uploads/resources/";
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $_FILES["resource_file"]["name"]);
            $target_file = $upload_dir . $filename;
            
            if(move_uploaded_file($_FILES["resource_file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
                $file_size = $_FILES["resource_file"]["size"];
                
                // Delete old file if editing
                if($edit_mode && $resource['file_path'] && file_exists($resource['file_path'])) {
                    unlink($resource['file_path']);
                }
            } else {
                $upload_success = false;
                $error_message = "Sorry, there was an error uploading your file. Please try again.";
            }
        } else {
            $upload_success = false;
            $error_message = "Sorry, only PDF, DOC, XLS, PPT, JPG, PNG, GIF, MP4, AVI, MOV, MP3, WAV files are allowed.";
        }
    } elseif(isset($_FILES["resource_file"]) && $_FILES["resource_file"]["error"] != UPLOAD_ERR_NO_FILE) {
        // Handle other file upload errors
        $upload_success = false;
        $error_message = "File upload error: " . getFileUploadError($_FILES["resource_file"]["error"]);
    }
    
    // Validate required fields
    if(empty($title) || empty($description) || empty($file_type) || empty($category)) {
        $upload_success = false;
        $error_message = "Please fill in all required fields.";
    }
    
    if($upload_success) {
        if($edit_mode) {
            // Update existing resource
            $sql = "UPDATE tbl_resources SET title = ?, description = ?, file_path = ?, file_type = ?, file_size = ?, category = ?, tags = ?, is_featured = ? WHERE resource_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssisssi", $title, $description, $file_path, $file_type, $file_size, $category, $tags, $is_featured, $resource_id);
        } else {
            // Insert new resource
            $sql = "INSERT INTO tbl_resources (title, description, file_path, file_type, file_size, category, tags, is_featured, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssisssi", $title, $description, $file_path, $file_type, $file_size, $category, $tags, $is_featured, $_SESSION['user_id']);
        }
        
        if(mysqli_stmt_execute($stmt)) {
            $success_message = $edit_mode ? "Resource updated successfully!" : "Resource added successfully!";
            
            if(!$edit_mode) {
                // Reset form for new entry
                $title = $description = $file_type = $category = $tags = '';
                $is_featured = 0;
            } else {
                // Refresh resource data
                $resource['title'] = $title;
                $resource['description'] = $description;
                $resource['file_type'] = $file_type;
                $resource['category'] = $category;
                $resource['tags'] = $tags;
                $resource['is_featured'] = $is_featured;
            }
        } else {
            $error_message = "Error saving resource. Please try again.";
        }
    }
}

// Get categories for dropdown
$categories_sql = "SELECT name FROM tbl_categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_sql);

// Helper function for file upload errors
function getFileUploadError($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
        case UPLOAD_ERR_PARTIAL:
            return "The uploaded file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder.";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the file upload.";
        default:
            return "Unknown upload error.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>
                <i class="fas fa-<?php echo $edit_mode ? 'edit' : 'plus-circle'; ?>"></i>
                <?php echo $edit_mode ? 'Edit Resource' : 'Add New Resource'; ?>
            </h1>
            <p><?php echo $edit_mode ? 'Update resource information' : 'Add a new educational resource to the library'; ?></p>
            <div class="header-actions">
                <a href="manage_resources.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Resources
                </a>
            </div>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- File Upload Limits Info -->
        <div class="upload-info-card">
            <div class="info-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="info-content">
                <h4>File Upload Information</h4>
                <p>Maximum file size: <strong><?php echo ini_get('upload_max_filesize'); ?></strong> | 
                   Maximum post size: <strong><?php echo ini_get('post_max_size'); ?></strong></p>
                <small>For larger files, please contact your system administrator to adjust PHP settings.</small>
            </div>
        </div>

        <div class="form-container-wide">
            <form method="POST" enctype="multipart/form-data" class="resource-form">
                <div class="form-section">
                    <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
                    
                    <div class="form-group">
                        <label for="title">Resource Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($resource['title'] ?? $title ?? ''); ?>" 
                               required maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="5" 
                                  required placeholder="Describe the resource..."><?php echo htmlspecialchars($resource['description'] ?? $description ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="file_type">File Type *</label>
                            <select id="file_type" name="file_type" class="form-control" required>
                                <option value="">Select File Type</option>
                                <option value="pdf" <?php echo ($resource['file_type'] ?? $file_type ?? '') == 'pdf' ? 'selected' : ''; ?>>PDF Document</option>
                                
                            </select> 
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php 
                                if($categories_result) {
                                    mysqli_data_seek($categories_result, 0); // Reset pointer
                                    while($cat = mysqli_fetch_assoc($categories_result)): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($cat['name']); ?>" 
                                        <?php echo ($resource['category'] ?? $category ?? '') == $cat['name'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php 
                                    endwhile; 
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-file-upload"></i> File Upload</h2>
                    
                    <div class="form-group">
                        <label for="resource_file">
                            <?php echo $edit_mode ? 'Update Resource File (Optional)' : 'Resource File *'; ?>
                        </label>
                        <input type="file" id="resource_file" name="resource_file" class="form-control-file" 
                               <?php echo $edit_mode ? '' : 'required'; ?>
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mp3,.wav">
                        <small class="form-text">
                            Maximum file size: <strong><?php echo ini_get('upload_max_filesize'); ?></strong><br>
                            Allowed formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, GIF, MP4, AVI, MOV, MP3, WAV
                            <?php if($edit_mode && $resource['file_path']): ?>
                                <br>Current file: <?php echo basename($resource['file_path']); ?>
                                (<?php echo formatFileSize($resource['file_size']); ?>)
                            <?php endif; ?>
                        </small>
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-tags"></i> Additional Information</h2>
                    
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" class="form-control" 
                               value="<?php echo htmlspecialchars($resource['tags'] ?? $tags ?? ''); ?>"
                               placeholder="Separate tags with commas (e.g., tutorial, beginner, advanced)">
                        <small class="form-text">Add relevant tags to help users find this resource</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   <?php echo ($resource['is_featured'] ?? $is_featured ?? 0) ? 'checked' : ''; ?>>
                            <label for="is_featured">Feature this resource on the homepage</label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-<?php echo $edit_mode ? 'save' : 'plus'; ?>"></i>
                        <?php echo $edit_mode ? 'Update Resource' : 'Add Resource'; ?>
                    </button>
                    <a href="manage_resources.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Helper function to format file size
function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>

<style>
.upload-info-card {
    background: var(--light-pink);
    border: 1px solid var(--medium-pink);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.upload-info-card .info-icon {
    color: #550236;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.upload-info-card .info-content h4 {
    margin: 0 0 0.5rem 0;
    color: #550236; 
}

.upload-info-card .info-content p {
    margin: 0 0 0.5rem 0;
    color: var(--text-color);
}

.upload-info-card .info-content small {
    color: var(--text-color);
    opacity: 0.7;
}
</style>

<?php include 'includes/footer.php'; ?>
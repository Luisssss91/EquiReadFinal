<?php
include 'config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Handle category actions
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['add_category'])) {
        $name = trim($_POST["name"]);
        $description = trim($_POST["description"]);
        $color = trim($_POST["color"]);
        $icon = trim($_POST["icon"]);
        
        // Check if category name already exists
        $check_sql = "SELECT COUNT(*) as count FROM tbl_categories WHERE name = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $name);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        $exists = mysqli_fetch_assoc($result);
        
        if($exists['count'] > 0) {
            $error_message = "Category name already exists. Please choose a different name.";
        } else {
            $sql = "INSERT INTO tbl_categories (name, description, color, icon) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $color, $icon);
            
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Category added successfully!";
                // Clear form
                $_POST = array();
            } else {
                $error_message = "Error adding category: " . mysqli_error($conn);
            }
        }
    } elseif(isset($_POST['edit_category'])) {
        $category_id = intval($_POST["category_id"]);
        $name = trim($_POST["name"]);
        $description = trim($_POST["description"]);
        $color = trim($_POST["color"]);
        $icon = trim($_POST["icon"]);
        
        // Check if category name already exists (excluding current category)
        $check_sql = "SELECT COUNT(*) as count FROM tbl_categories WHERE name = ? AND category_id != ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "si", $name, $category_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        $exists = mysqli_fetch_assoc($result);
        
        if($exists['count'] > 0) {
            $error_message = "Category name already exists. Please choose a different name.";
        } else {
            $sql = "UPDATE tbl_categories SET name = ?, description = ?, color = ?, icon = ? WHERE category_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $name, $description, $color, $icon, $category_id);
            
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Category updated successfully!";
                // Clear edit mode
                $edit_category = null;
                // Refresh categories list
                header("Location: manage_categories.php?success=updated");
                exit();
            } else {
                $error_message = "Error updating category: " . mysqli_error($conn);
            }
        }
    }
}

// Handle delete action
if(isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    
    // Check if category is being used
    $check_sql = "SELECT COUNT(*) as count FROM tbl_resources WHERE category = (SELECT name FROM tbl_categories WHERE category_id = ?)";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usage = mysqli_fetch_assoc($result);
    
    if($usage['count'] == 0) {
        $delete_sql = "DELETE FROM tbl_categories WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($stmt, "i", $category_id);
        
        if(mysqli_stmt_execute($stmt)) {
            $success_message = "Category deleted successfully!";
            header("Location: manage_categories.php?success=deleted");
            exit();
        } else {
            $error_message = "Error deleting category: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Cannot delete category. It is being used by " . $usage['count'] . " resource(s).";
    }
}

// Show success message from redirect
if(isset($_GET['success'])) {
    if($_GET['success'] == 'updated') {
        $success_message = "Category updated successfully!";
    } elseif($_GET['success'] == 'deleted') {
        $success_message = "Category deleted successfully!";
    }
}

// Get all categories
$categories_sql = "SELECT * FROM tbl_categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_sql);

// Get category for editing
$edit_category = null;
if(isset($_GET['edit'])) {
    $category_id = intval($_GET['edit']);
    $sql = "SELECT * FROM tbl_categories WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_category = mysqli_fetch_assoc($result);
    
    // If category not found, clear edit mode
    if(!$edit_category) {
        $edit_category = null;
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tags"></i> Manage Categories</h1>
            <p>Organize resources into categories for better navigation</p>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="management-grid">
            <div class="management-form">
                <div class="form-container">
                    <h2>
                        <i class="fas fa-<?php echo $edit_category ? 'edit' : 'plus'; ?>"></i>
                        <?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?>
                    </h2>
                    
                    <form method="POST">
                        <?php if($edit_category): ?>
                            <input type="hidden" name="category_id" value="<?php echo $edit_category['category_id']; ?>">
                            <input type="hidden" name="edit_category" value="1">
                        <?php else: ?>
                            <input type="hidden" name="add_category" value="1">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="name">Category Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($edit_category['name'] ?? ($_POST['name'] ?? '')); ?>" 
                                   required maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"
                                      placeholder="Brief description of this category..."><?php echo htmlspecialchars($edit_category['description'] ?? ($_POST['description'] ?? '')); ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="color">Color *</label>
                                <div class="color-input-container">
                                    <input type="color" id="color" name="color" class="form-control-color" 
                                           value="<?php echo htmlspecialchars($edit_category['color'] ?? ($_POST['color'] ?? '#ff85a2')); ?>" required>
                                    <span class="color-value"><?php echo htmlspecialchars($edit_category['color'] ?? ($_POST['color'] ?? '#ff85a2')); ?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="icon">Icon *</label>
                                <select id="icon" name="icon" class="form-control" required>
                                    <option value="fas fa-folder" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-folder' ? 'selected' : ''; ?>>📁 Folder</option>
                                    <option value="fas fa-book" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-book' ? 'selected' : ''; ?>>📚 Book</option>
                                    <option value="fas fa-flask" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-flask' ? 'selected' : ''; ?>>🧪 Flask</option>
                                    <option value="fas fa-calculator" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-calculator' ? 'selected' : ''; ?>>🧮 Calculator</option>
                                    <option value="fas fa-laptop-code" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-laptop-code' ? 'selected' : ''; ?>>💻 Laptop</option>
                                    <option value="fas fa-palette" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-palette' ? 'selected' : ''; ?>>🎨 Palette</option>
                                    <option value="fas fa-music" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-music' ? 'selected' : ''; ?>>🎵 Music</option>
                                    <option value="fas fa-globe" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-globe' ? 'selected' : ''; ?>>🌎 Globe</option>
                                    <option value="fas fa-history" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-history' ? 'selected' : ''; ?>>📜 History</option>
                                    <option value="fas fa-dna" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-dna' ? 'selected' : ''; ?>>🧬 DNA</option>
                                    <option value="fas fa-language" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-language' ? 'selected' : ''; ?>>🔤 Language</option>
                                    <option value="fas fa-chart-bar" <?php echo ($edit_category['icon'] ?? ($_POST['icon'] ?? '')) == 'fas fa-chart-bar' ? 'selected' : ''; ?>>📊 Chart</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-<?php echo $edit_category ? 'save' : 'plus'; ?>"></i>
                                <?php echo $edit_category ? 'Update Category' : 'Add Category'; ?>
                            </button>
                            
                            <?php if($edit_category): ?>
                                <a href="manage_categories.php" class="btn btn-outline">Cancel Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="management-list">
                <div class="management-card">
                    <div class="card-header">
                        <h2><i class="fas fa-list"></i> Existing Categories</h2>
                        <div class="header-actions">
                            <span class="total-count">Total: <?php echo mysqli_num_rows($categories_result); ?> categories</span>
                        </div>
                    </div>

                    <div class="card-content">
                        <?php if(mysqli_num_rows($categories_result) > 0): ?>
                            <div class="categories-grid">
                                <?php 
                                // Reset result pointer
                                mysqli_data_seek($categories_result, 0);
                                while($category = mysqli_fetch_assoc($categories_result)): 
                                ?>
                                <div class="category-management-card" style="--category-color: <?php echo $category['color']; ?>">
                                    <div class="category-header">
                                        <div class="category-icon">
                                            <i class="<?php echo $category['icon']; ?>"></i>
                                        </div>
                                        <div class="category-actions">
                                            <a href="manage_categories.php?edit=<?php echo $category['category_id']; ?>" 
                                               class="btn btn-small btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="manage_categories.php?delete=<?php echo $category['category_id']; ?>" 
                                               class="btn btn-small btn-danger" title="Delete"
                                               onclick="return confirm('Are you sure you want to delete the category \'<?php echo addslashes($category['name']); ?>\'? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                    
                                    <?php if($category['description']): ?>
                                        <p><?php echo htmlspecialchars($category['description']); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="category-meta">
                                        <?php
                                        $resource_count_sql = "SELECT COUNT(*) as count FROM tbl_resources WHERE category = ?";
                                        $stmt = mysqli_prepare($conn, $resource_count_sql);
                                        mysqli_stmt_bind_param($stmt, "s", $category['name']);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        $resource_count = mysqli_fetch_assoc($result)['count'];
                                        ?>
                                        <span class="resource-count">
                                            <i class="fas fa-file"></i> <?php echo $resource_count; ?> resources
                                        </span>
                                        <span class="category-color" style="background: <?php echo $category['color']; ?>"></span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-data">
                                <i class="fas fa-tags fa-3x"></i>
                                <h3>No Categories Found</h3>
                                <p>Create your first category to organize resources.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.management-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    margin-top: 2rem;
}

.form-container {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 15px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.color-input-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-control-color {
    width: 60px;
    height: 40px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.color-value {
    font-family: monospace;
    font-size: 0.9rem;
    color: var(--text-color);
    opacity: 0.8;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.category-management-card {
    background: white;
    border: 2px solid var(--category-color, #ff85a2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
}

.category-management-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.3);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.category-icon {
    width: 50px;
    height: 50px;
    background: var(--category-color, #ff85a2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.category-actions {
    display: flex;
    gap: 0.5rem;
}

.category-management-card h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-color);
    font-size: 1.2rem;
}

.category-management-card p {
    margin: 0 0 1rem 0;
    color: var(--text-color);
    opacity: 0.8;
    line-height: 1.4;
}

.category-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--light-pink);
}

.resource-count {
    font-size: 0.9rem;
    color: var(--text-color);
    opacity: 0.7;
}

.category-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-small {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

@media (max-width: 1024px) {
    .management-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Update color value display when color picker changes
document.getElementById('color')?.addEventListener('input', function(e) {
    const colorValue = this.nextElementSibling;
    if(colorValue) {
        colorValue.textContent = e.target.value;
    }
});

// Show confirmation for delete with category name
function confirmDelete(categoryName) {
    return confirm(`Are you sure you want to delete the category "${categoryName}"? This action cannot be undone.`);
}
</script>

<?php include 'includes/footer.php'; ?>
[file content end]
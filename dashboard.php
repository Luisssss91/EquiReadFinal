<?php
include 'config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

// Get statistics with actual database queries
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_users"))['count'];
$resources_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_resources"))['count'];
$downloads_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_downloads"))['count'];
$categories_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_categories"))['count'];

// Get recent users (last 5)
$recent_users = mysqli_query($conn, "
    SELECT user_id, full_name, email, role, profile_picture, join_date, last_login 
    FROM tbl_users 
    ORDER BY created_at DESC 
    LIMIT 5
");

// Get recent resources (last 5)
$recent_resources = mysqli_query($conn, "
    SELECT r.resource_id, r.title, r.file_type, r.upload_date, r.downloads_count, u.full_name 
    FROM tbl_resources r 
    LEFT JOIN tbl_users u ON r.uploaded_by = u.user_id 
    ORDER BY r.upload_date DESC 
    LIMIT 5
");

// Get popular resources (top 5 by downloads)
$popular_resources = mysqli_query($conn, "
    SELECT r.resource_id, r.title, r.downloads_count, r.file_type, r.category
    FROM tbl_resources r 
    ORDER BY r.downloads_count DESC 
    LIMIT 5
");

// Get download statistics for chart (last 7 days) - IMPROVED QUERY
$download_stats = mysqli_query($conn, "
    SELECT 
        DATE(download_date) as date, 
        COUNT(*) as count,
        DAYNAME(download_date) as day_name,
        DAY(download_date) as day_number
    FROM tbl_downloads 
    WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(download_date) 
    ORDER BY date ASC
");

// Get download statistics by category for the chart
$category_downloads = mysqli_query($conn, "
    SELECT 
        r.category,
        COUNT(d.download_id) as downloads,
        c.color
    FROM tbl_downloads d
    JOIN tbl_resources r ON d.resource_id = r.resource_id
    LEFT JOIN tbl_categories c ON r.category = c.name
    WHERE d.download_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY r.category, c.color
    ORDER BY downloads DESC
    LIMIT 5
");

// Get today's downloads count
$today_downloads = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as count FROM tbl_downloads 
    WHERE DATE(download_date) = CURDATE()
"))['count'];

// Get yesterday's downloads for comparison
$yesterday_downloads = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as count FROM tbl_downloads 
    WHERE DATE(download_date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
"))['count'];

// Calculate download growth percentage
$download_growth = $yesterday_downloads > 0 ? 
    round((($today_downloads - $yesterday_downloads) / $yesterday_downloads) * 100, 1) : 
    ($today_downloads > 0 ? 100 : 0);

// Get average daily downloads for the week
$avg_daily_downloads = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT ROUND(AVG(daily_count), 1) as avg_downloads
    FROM (
        SELECT DATE(download_date) as date, COUNT(*) as daily_count
        FROM tbl_downloads 
        WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(download_date)
    ) as daily_stats
"))['avg_downloads'];

// Get category distribution
$category_stats = mysqli_query($conn, "
    SELECT c.name, c.color, COUNT(r.resource_id) as resource_count
    FROM tbl_categories c 
    LEFT JOIN tbl_resources r ON c.name = r.category 
    GROUP BY c.name, c.color
    ORDER BY resource_count DESC
");

// Get system information
$total_file_size = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(file_size) as total FROM tbl_resources"))['total'];
$total_file_size_formatted = formatFileSize($total_file_size ?: 0);

// Get weekly growth
$last_week_users = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as count FROM tbl_users 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) 
    AND created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"))['count'];
$this_week_users = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as count FROM tbl_users 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"))['count'];

$user_growth = $last_week_users > 0 ? 
    round((($this_week_users - $last_week_users) / $last_week_users) * 100, 1) : 
    ($this_week_users > 0 ? 100 : 0);
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back, <?php echo $_SESSION['full_name']; ?>! <i class="fas fa-heart"></i></p>
            <div class="dashboard-time">
                <i class="fas fa-clock"></i> <?php echo date('l, F j, Y'); ?>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Users</h3>
                    <p class="stat-number"><?php echo $users_count; ?></p>
                    <div class="stat-subtitle">
                        <span class="stat-trend <?php echo $user_growth >= 0 ? 'positive' : 'negative'; ?>">
                            <i class="fas fa-arrow-<?php echo $user_growth >= 0 ? 'up' : 'down'; ?>"></i>
                            <?php echo abs($user_growth); ?>%
                        </span>
                        this week
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>Resources</h3>
                    <p class="stat-number"><?php echo $resources_count; ?></p>
                    <div class="stat-subtitle">
                        <span class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            8%
                        </span>
                        this month
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-info">
                    <h3>Downloads</h3>
                    <p class="stat-number"><?php echo $downloads_count; ?></p>
                    <div class="stat-subtitle">
                        <span class="stat-trend <?php echo $download_growth >= 0 ? 'positive' : 'negative'; ?>">
                            <i class="fas fa-arrow-<?php echo $download_growth >= 0 ? 'up' : 'down'; ?>"></i>
                            <?php echo abs($download_growth); ?>%
                        </span>
                        today
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-info">
                    <h3>Storage Used</h3>
                    <p class="stat-number"><?php echo $total_file_size_formatted; ?></p>
                    <div class="stat-subtitle">
                        <?php echo $resources_count; ?> files
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            <div class="action-buttons">
                <a href="manage_users.php" class="action-btn">
                    <i class="fas fa-users-cog"></i>
                    <span>Manage Users</span>
                    <small><?php echo $users_count; ?> users</small>
                </a>
                <a href="manage_resources.php" class="action-btn">
                    <i class="fas fa-book-medical"></i>
                    <span>Manage Resources</span>
                    <small><?php echo $resources_count; ?> resources</small>
                </a>
                <a href="add_resource.php" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Resource</span>
                    <small>Upload new file</small>
                </a>
                <a href="manage_categories.php" class="action-btn">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                    <small><?php echo $categories_count; ?> categories</small>
                </a>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Recent Users -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-user-friends"></i> Recent Users</h3>
                    <a href="manage_users.php" class="view-all">View All</a>
                </div>
                <div class="card-content">
                    <?php if(mysqli_num_rows($recent_users) > 0): ?>
                        <div class="user-list">
                            <?php while($user = mysqli_fetch_assoc($recent_users)): ?>
                            <div class="user-item">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="user-details">
                                    <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                                    <span class="user-role <?php echo $user['role']; ?>">
                                        <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'crown' : 'user'; ?>"></i>
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </div>
                                <div class="user-join-date">
                                    <?php echo date('M j', strtotime($user['join_date'])); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No users found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Resources -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-file-alt"></i> Recent Resources</h3>
                    <a href="manage_resources.php" class="view-all">View All</a>
                </div>
                <div class="card-content">
                    <?php if(mysqli_num_rows($recent_resources) > 0): ?>
                        <div class="resource-list">
                            <?php while($resource = mysqli_fetch_assoc($recent_resources)): ?>
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fas fa-file-<?php echo getFileTypeIcon($resource['file_type']); ?>"></i>
                                </div>
                                <div class="resource-details">
                                    <h4><?php echo htmlspecialchars($resource['title']); ?></h4>
                                    <p>By <?php echo htmlspecialchars($resource['full_name']); ?></p>
                                    <div class="resource-meta-small">
                                        <span class="downloads">
                                            <i class="fas fa-download"></i> <?php echo $resource['downloads_count']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="resource-date">
                                    <?php echo date('M j', strtotime($resource['upload_date'])); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No resources found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular Resources -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Popular Resources</h3>
                    <a href="manage_resources.php" class="view-all">View All</a>
                </div>
                <div class="card-content">
                    <?php if(mysqli_num_rows($popular_resources) > 0): ?>
                        <div class="popular-list">
                            <?php 
                            $rank = 1;
                            while($resource = mysqli_fetch_assoc($popular_resources)): 
                            ?>
                            <div class="popular-item">
                                <div class="popular-rank">
                                    <span class="rank-number">#<?php echo $rank; ?></span>
                                    <small><?php echo $resource['downloads_count']; ?> downloads</small>
                                </div>
                                <div class="popular-details">
                                    <h4><?php echo htmlspecialchars($resource['title']); ?></h4>
                                    <div class="file-type">
                                        <i class="fas fa-file-<?php echo getFileTypeIcon($resource['file_type']); ?>"></i>
                                        <?php echo strtoupper($resource['file_type']); ?>
                                    </div>
                                    <div class="resource-category-small">
                                        <?php echo htmlspecialchars($resource['category'] ?: 'General'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $rank++;
                            endwhile; 
                            ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No popular resources.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Status -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-heartbeat"></i> System Status</h3>
                </div>
                <div class="card-content">
                    <div class="status-list">
                        <div class="status-item status-good">
                            <i class="fas fa-check-circle"></i>
                            <span>System Online</span>
                            <small>All systems operational</small>
                        </div>
                        <div class="status-item status-good">
                            <i class="fas fa-database"></i>
                            <span>Database Connected</span>
                            <small>MySQL server running</small>
                        </div>
                        <div class="status-item <?php echo ($total_file_size > 500000000) ? 'status-warning' : 'status-good'; ?>">
                            <i class="fas fa-hdd"></i>
                            <span>Storage Usage</span>
                            <small><?php echo $total_file_size_formatted; ?> used</small>
                        </div>
                        <div class="status-item status-good">
                            <i class="fas fa-shield-alt"></i>
                            <span>Security Active</span>
                            <small>Protected and secure</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Statistics -->
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h3><i class="fas fa-chart-bar"></i> Download Statistics (Last 7 Days)</h3>
                    <div class="chart-summary">
                        <div class="summary-item">
                            <span class="summary-label">Today:</span>
                            <span class="summary-value"><?php echo $today_downloads; ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Avg Daily:</span>
                            <span class="summary-value"><?php echo $avg_daily_downloads ?: '0'; ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Growth:</span>
                            <span class="summary-value <?php echo $download_growth >= 0 ? 'positive' : 'negative'; ?>">
                                <i class="fas fa-arrow-<?php echo $download_growth >= 0 ? 'up' : 'down'; ?>"></i>
                                <?php echo abs($download_growth); ?>%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <?php if(mysqli_num_rows($download_stats) > 0): ?>
                        <div class="chart-container">
                            <div class="chart-bars-horizontal">
                                <?php 
                                $max_downloads = 0;
                                $stats_data = [];
                                
                                // Generate last 7 days array to fill missing dates
                                $last_7_days = [];
                                for($i = 6; $i >= 0; $i--) {
                                    $date = date('Y-m-d', strtotime("-$i days"));
                                    $last_7_days[$date] = [
                                        'date' => $date,
                                        'count' => 0,
                                        'day_name' => date('D', strtotime($date)),
                                        'day_number' => date('j', strtotime($date))
                                    ];
                                }

                                // Fill with actual data
                                while($stat = mysqli_fetch_assoc($download_stats)) {
                                    $last_7_days[$stat['date']] = $stat;
                                    if($stat['count'] > $max_downloads) {
                                        $max_downloads = $stat['count'];
                                    }
                                }

                                $stats_data = array_values($last_7_days);
                                $max_downloads = max(10, $max_downloads); // Ensure minimum scale
                                
                                foreach($stats_data as $stat): 
                                    $percentage = $max_downloads > 0 ? ($stat['count'] / $max_downloads) * 100 : 0;
                                    $is_today = $stat['date'] == date('Y-m-d');
                                ?>
                                <div class="chart-bar-horizontal <?php echo $is_today ? 'today' : ''; ?>">
                                    <div class="bar-info">
                                        <span class="bar-day"><?php echo $stat['day_name']; ?></span>
                                        <span class="bar-date"><?php echo $stat['day_number']; ?></span>
                                    </div>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: <?php echo $percentage; ?>%">
                                            <span class="bar-count"><?php echo $stat['count']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Category Downloads Breakdown -->
                        <div class="category-breakdown">
                            <h4>Top Categories This Week</h4>
                            <div class="category-bars">
                                <?php if(mysqli_num_rows($category_downloads) > 0): ?>
                                    <?php while($category = mysqli_fetch_assoc($category_downloads)): ?>
                                    <div class="category-bar">
                                        <div class="category-info">
                                            <span class="category-name"><?php echo htmlspecialchars($category['category'] ?: 'Uncategorized'); ?></span>
                                            <span class="category-count"><?php echo $category['downloads']; ?> downloads</span>
                                        </div>
                                        <div class="category-bar-fill" style="background-color: <?php echo $category['color'] ?: '#ff85a2'; ?>; width: <?php echo min(100, ($category['downloads'] / max(1, $today_downloads)) * 100); ?>%"></div>
                                    </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="no-data-small">No category download data available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-chart-bar fa-2x"></i>
                            <p>No download data available for the last 7 days.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Category Distribution</h3>
                </div>
                <div class="card-content">
                    <?php if(mysqli_num_rows($category_stats) > 0): ?>
                        <div class="category-distribution">
                            <div class="distribution-chart">
                                <?php
                                $total_resources = $resources_count;
                                $current_angle = 0;
                                
                                while($category = mysqli_fetch_assoc($category_stats)):
                                    $percentage = $total_resources > 0 ? ($category['resource_count'] / $total_resources) * 100 : 0;
                                    $angle = ($percentage / 100) * 360;
                                ?>
                                <div class="chart-segment" 
                                     style="--segment-color: <?php echo $category['color']; ?>; 
                                            --segment-percentage: <?php echo $percentage; ?>%;
                                            --start-angle: <?php echo $current_angle; ?>deg;
                                            --end-angle: <?php echo $current_angle + $angle; ?>deg;">
                                </div>
                                <?php 
                                    $current_angle += $angle;
                                endwhile; 
                                ?>
                            </div>
                            <div class="distribution-legend">
                                <?php 
                                mysqli_data_seek($category_stats, 0); // Reset pointer
                                while($category = mysqli_fetch_assoc($category_stats)): 
                                    $percentage = $total_resources > 0 ? round(($category['resource_count'] / $total_resources) * 100, 1) : 0;
                                ?>
                                <div class="legend-item">
                                    <span class="legend-color" style="background: <?php echo $category['color']; ?>"></span>
                                    <span class="legend-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                    <span class="legend-count"><?php echo $category['resource_count']; ?> resources</span>
                                    <span class="legend-percentage"><?php echo $percentage; ?>%</span>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-tags fa-2x"></i>
                            <p>No categories available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Improved Download Statistics Styles */
.chart-summary {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.summary-label {
    font-size: 0.8rem;
    color: var(--text-color);
    opacity: 0.7;
}

.summary-value {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--primary-color);
}

.summary-value.positive {
    color: #10b981;
}

.summary-value.negative {
    color: #ef4444;
}

.chart-bars-horizontal {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.chart-bar-horizontal {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chart-bar-horizontal.today .bar-info {
    font-weight: 700;
    color: var(--primary-color);
}

.chart-bar-horizontal.today .bar-fill {
    background: linear-gradient(90deg, var(--primary-color), #ff6b9c);
}

.bar-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 60px;
    flex-shrink: 0;
}

.bar-day {
    font-size: 0.8rem;
    color: var(--text-color);
    opacity: 0.8;
}

.bar-date {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color);
}

.bar-container {
    flex: 1;
    height: 30px;
    background: var(--light-pink);
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #ff85a2, #ff6b9c);
    border-radius: 15px;
    transition: width 0.8s ease;
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 15px;
    min-width: 40px;
}

.bar-count {
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.category-breakdown {
    border-top: 1px solid var(--medium-pink);
    padding-top: 1.5rem;
}

.category-breakdown h4 {
    margin-bottom: 1rem;
    color: var(--text-color);
    font-size: 1rem;
}

.category-bars {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.category-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.category-info {
    display: flex;
    justify-content: space-between;
    width: 200px;
    flex-shrink: 0;
}

.category-name {
    font-weight: 500;
    color: var(--text-color);
}

.category-count {
    font-size: 0.8rem;
    color: var(--text-color);
    opacity: 0.7;
}

.category-bar-fill {
    flex: 1;
    height: 8px;
    border-radius: 4px;
    transition: width 0.8s ease;
    min-width: 20px;
}

.no-data-small {
    text-align: center;
    color: var(--text-color);
    opacity: 0.7;
    font-style: italic;
    padding: 1rem;
}
</style>

<?php
// Helper functions
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

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>

<?php include 'includes/footer.php'; ?>

<?php
// setup_check.php
include 'config.php';

echo "<h2>System Setup Check</h2>";

// Check database connection
if($conn) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
}

// Check directories
$dirs = ['uploads', 'uploads/resources'];
foreach($dirs as $dir) {
    if(!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created directory: $dir<br>";
    } else {
        echo "✅ Directory exists: $dir<br>";
    }
    
    if(is_writable($dir)) {
        echo "✅ Directory writable: $dir<br>";
    } else {
        echo "❌ Directory not writable: $dir<br>";
    }
}

// Check required PHP extensions
$extensions = ['mysqli', 'gd', 'mbstring'];
foreach($extensions as $ext) {
    if(extension_loaded($ext)) {
        echo "✅ PHP extension loaded: $ext<br>";
    } else {
        echo "❌ PHP extension missing: $ext<br>";
    }
}
?>
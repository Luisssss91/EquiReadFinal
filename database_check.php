<?php
// database_check.php
include 'config.php';

$required_tables = ['tbl_users', 'tbl_resources', 'tbl_categories', 'tbl_downloads'];

foreach($required_tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    echo "Table $table exists: " . (mysqli_num_rows($result) > 0 ? 'Yes' : 'No') . "<br>";
}
?>
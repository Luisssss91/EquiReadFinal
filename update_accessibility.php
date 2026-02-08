<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $profile = $_POST['profile'];
    $user_id = $_SESSION['user_id'];
    
    $sql = "UPDATE tbl_users SET accessibility_profile = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $profile, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['accessibility_profile'] = $profile;
        echo "success";
    } else {
        echo "error";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "invalid";
}

mysqli_close($conn);
?>
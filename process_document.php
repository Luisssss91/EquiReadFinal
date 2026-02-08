<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();

// For testing
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

include 'config.php';

// Get resource_id
$resource_id = isset($_GET['resource_id']) ? intval($_GET['resource_id']) : 0;

if ($resource_id <= 0) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid resource ID',
        'received_id' => $resource_id
    ]);
    exit;
}

// Get resource from database
$sql = "SELECT r.* FROM tbl_resources r WHERE r.resource_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $resource_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resource = mysqli_fetch_assoc($result);

if (!$resource) {
    echo json_encode([
        'success' => false,
        'error' => 'Resource not found'
    ]);
    exit;
}

$title = $resource['title'] ?? 'Untitled';
$description = $resource['description'] ?? 'No description';
$file_type = strtolower($resource['file_type'] ?? '');
$file_path = $resource['file_path'] ?? '';

// Check if file exists
$file_exists = false;
if ($file_path && file_exists($file_path)) {
    $file_exists = true;
}

// For non-PDF files, return description
$text = "Title: " . $title . ". ";
$text .= "Description: " . $description . ". ";
$text .= "File Type: " . strtoupper($file_type) . ". ";

if ($file_exists) {
    $text .= "Note: For PDF files, text is extracted directly in your browser. ";
    $text .= "For other file types, please download the file to view its contents.";
} else {
    $text .= "Note: File not found at: " . $file_path;
}

echo json_encode([
    'success' => true,
    'text' => $text,
    'cached' => false,
    'fallback' => true,
    'method' => 'description_only',
    'file_type' => $file_type,
    'title' => $title,
    'file_exists' => $file_exists,
    'file_path' => $file_path
]);
?>
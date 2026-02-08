<?php
// permissions_check.php
echo "Uploads directory exists: " . (is_dir('uploads') ? 'Yes' : 'No') . "<br>";
echo "Uploads directory writable: " . (is_writable('uploads') ? 'Yes' : 'No') . "<br>";
echo "Resources directory exists: " . (is_dir('uploads/resources') ? 'Yes' : 'No') . "<br>";
echo "Resources directory writable: " . (is_writable('uploads/resources') ? 'Yes' : 'No') . "<br>";
?>
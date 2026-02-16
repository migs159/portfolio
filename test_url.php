<?php
define('BASEPATH', '/');
require 'system/core/CodeIgniter.php';

// This would normally be loaded by CI boot, but we can check manually
$base_url_value = 'http://localhost/portfolio/';
$relative_path = 'assets/img/uploads/project_1771221706_2397.jpg';

// Simulate what the portfolio view does
if ($relative_path && strpos($relative_path, 'http') !== 0 && strpos($relative_path, '//') !== 0) {
    $full_url = $base_url_value . $relative_path;
} else {
    $full_url = $relative_path;
}

echo "Relative path: " . $relative_path . "\n";
echo "Full URL should be: " . $full_url . "\n";
echo "Test URL: http://localhost/portfolio/assets/img/uploads/project_1771221706_2397.jpg\n";
?>

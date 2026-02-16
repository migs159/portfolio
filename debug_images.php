<?php
$mysqli = new mysqli('localhost', 'root', '', 'portfolio');
$result = $mysqli->query("SELECT id, title, image, featured FROM projects WHERE deleted_at IS NULL ORDER BY title");

if (!$result) {
    echo "Query Error: " . $mysqli->error . "\n";
} else {
    while($row = $result->fetch_assoc()) {
        echo "Title: " . $row['title'] . "\n";
        echo "Image: " . ($row['image'] ?: 'NULL') . "\n";
        echo "Featured: " . $row['featured'] . "\n";
        echo "---\n";
    }
}
$mysqli->close();
?>

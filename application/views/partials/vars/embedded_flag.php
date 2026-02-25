<?php
// Partial: embedded_flag.php (moved to vars/)
$embedded = false;
if (isset($_GET['embedded']) && ($_GET['embedded'] === '1' || $_GET['embedded'] === 1)) {
    $embedded = true;
}
?>

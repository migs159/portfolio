<?php
// Partial: embedded_flag.php
// Determines whether the current view is embedded (via ?embedded=1)
$embedded = false;
if (isset($_GET['embedded']) && ($_GET['embedded'] === '1' || $_GET['embedded'] === 1)) {
    $embedded = true;
}
?>

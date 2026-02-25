<?php
// Proxy loader: footer moved to partials/footers/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/footers/footer');
    return;
}
include __DIR__ . '/footers/footer.php';


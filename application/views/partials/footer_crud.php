<?php
// Proxy loader: footer_crud moved to partials/footers/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/footers/footer_crud');
    return;
}
include __DIR__ . '/footers/footer_crud.php';


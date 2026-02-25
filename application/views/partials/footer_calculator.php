<?php
// Proxy loader: footer_calculator moved to partials/footers/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/footers/footer_calculator');
    return;
}
include __DIR__ . '/footers/footer_calculator.php';


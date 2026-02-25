<?php
// Proxy loader: embedded_flag moved to partials/vars/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/vars/embedded_flag');
    return;
}
include __DIR__ . '/vars/embedded_flag.php';


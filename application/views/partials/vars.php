<?php
// Proxy loader: vars moved to partials/vars/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/vars/vars');
    return;
}
include __DIR__ . '/vars/vars.php';


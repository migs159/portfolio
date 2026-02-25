<?php
// Proxy loader: header_crud now lives in partials/headers/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/headers/header_crud');
    return;
}
include __DIR__ . '/headers/header_crud.php';


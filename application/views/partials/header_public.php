<?php
// Proxy loader: header_public now lives in partials/headers/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/headers/header_public');
    return;
}
include __DIR__ . '/headers/header_public.php';


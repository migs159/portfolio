<?php
// Proxy loader: contact_item moved to partials/cards/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/cards/contact_item');
    return;
}
include __DIR__ . '/cards/contact_item.php';


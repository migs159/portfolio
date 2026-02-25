<?php
// Proxy loader: modal_delete_project moved to partials/modals/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/modals/modal_delete_project');
    return;
}
include __DIR__ . '/modals/modal_delete_project.php';


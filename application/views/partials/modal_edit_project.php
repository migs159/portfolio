<?php
// Proxy loader: modal_edit_project moved to partials/modals/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/modals/modal_edit_project');
    return;
}
include __DIR__ . '/modals/modal_edit_project.php';


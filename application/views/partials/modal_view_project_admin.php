<?php
// Proxy loader: modal moved to partials/modals/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/modals/modal_view_project_admin');
    return;
}
include __DIR__ . '/modals/modal_view_project_admin.php';


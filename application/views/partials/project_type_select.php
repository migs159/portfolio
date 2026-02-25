<?php
// Proxy loader: project_type_select moved to partials/forms/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/forms/project_type_select');
    return;
}
include __DIR__ . '/forms/project_type_select.php';


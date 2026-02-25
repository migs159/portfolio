<?php
// Proxy loader: project_card moved to partials/cards/
if (function_exists('get_instance')) {
    get_instance()->load->view('partials/cards/project_card');
    return;
}
include __DIR__ . '/cards/project_card.php';


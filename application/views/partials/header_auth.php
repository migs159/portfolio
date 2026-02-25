<?php
// Proxy loader: header_auth now lives in partials/headers/
if (function_exists('get_instance')) {
	get_instance()->load->view('partials/headers/header_auth');
	return;
}
include __DIR__ . '/headers/header_auth.php';


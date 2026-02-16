<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form']);
        $this->load->model('User_model');
        $this->load->model('Project_model');
        
        // Redirect to login if not logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Source - https://stackoverflow.com/q/77147541
// Posted by Shubham Nayak
// Retrieved 2026-02-16, License - CC BY-SA 4.0

$this->output->set_header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inine';");

    
    }

    public function index()
    {
        $data['page_title'] = 'CRUD Dashboard';
        // pass any login flash message to the view
        $data['login_success'] = $this->session->flashdata('login_success');
        $projects = $this->Project_model->get_all();
        $data['projects_count'] = is_array($projects) ? count($projects) : 0;
        // Pass projects to the view (used as $events in the view for backward compatibility)
        $data['events'] = $projects;
        // Prepare profile initial for navbar (first letter of username)
        $username = $this->session->userdata('username') ?: '';
        $data['__profile_initial'] = $username ? strtoupper(mb_substr($username, 0, 1)) : 'U';
        // Load current user row (so view can show email and other fields)
        $data['user'] = [];
        if ($username) {
            $userRow = $this->User_model->get_by_username($username);
            if ($userRow) {
                $data['user'] = $userRow;
                // also make email available in session for backward compatibility
                if (! $this->session->userdata('email') && ! empty($userRow['email'])) {
                    $this->session->set_userdata('email', $userRow['email']);
                }
            }
        }
        $this->load->view('crud_dashboard', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('portfolio');
    }
}

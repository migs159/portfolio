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
    }

    public function index()
    {
        $data['page_title'] = 'CRUD Dashboard';
        // pass any login flash message to the view
        $data['login_success'] = $this->session->flashdata('login_success');
        $projects = $this->Project_model->get_all();
        $data['projects_count'] = is_array($projects) ? count($projects) : 0;
        $this->load->view('crud_dashboard', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('portfolio');
    }
}

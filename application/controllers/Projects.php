<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form']);
        $this->load->model('Project_model');
    }

    protected function require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->require_login()) return;
        $data['projects'] = $this->Project_model->get_all();
        $this->load->view('projects_index', $data);
    }

    public function create()
    {
        if (!$this->require_login()) return;
        if ($this->input->method() === 'post') {
            $payload = $this->input->post();
            $payload['tags'] = isset($payload['tags']) ? array_map('trim', explode(',', $payload['tags'])) : [];
            $this->Project_model->create($payload);
            redirect('projects');
        }
        $this->load->view('project_form');
    }

    public function edit($id = null)
    {
        if (!$this->require_login()) return;
        if (!$id) redirect('projects');
        if ($this->input->method() === 'post') {
            $payload = $this->input->post();
            $payload['tags'] = isset($payload['tags']) ? array_map('trim', explode(',', $payload['tags'])) : [];
            $this->Project_model->update($id, $payload);
            redirect('projects');
        }
        $data['project'] = $this->Project_model->get($id);
        $this->load->view('project_form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->require_login()) return;
        if ($id) {
            $this->Project_model->delete($id);
        }
        redirect('projects');
    }
}

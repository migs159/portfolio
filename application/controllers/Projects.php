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
        // If not requested as embedded, redirect back to the CRUD dashboard
        $embedded = $this->input->get('embedded');
        if (empty($embedded)) {
            redirect('crud');
            return;
        }

        $mode = $this->input->get('mode') ?: 'read';
        $allowed = ['read','update','delete'];
        if (!in_array($mode, $allowed)) $mode = 'read';
        $data['mode'] = $mode;
        $data['projects'] = $this->Project_model->get_all();
        $this->load->view('projects_index', $data);
    }

    public function create()
    {
        if (!$this->require_login()) return;
        if ($this->input->method() === 'post') {
            $payload = $this->input->post();
            $payload['tags'] = isset($payload['tags']) ? array_map('trim', explode(',', $payload['tags'])) : [];
            $id = $this->Project_model->create($payload);
            if ($id) {
                $this->session->set_flashdata('success', 'Project created successfully.');
            } else {
                $this->session->set_flashdata('error', 'Unable to create project.');
            }
            // Allow quick-create form to request returning to the CRUD dashboard
            $return_to = $this->input->post('return_to');
            // If this was an AJAX request, return JSON instead of redirecting
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                if ($id) echo json_encode(['success' => true, 'message' => 'Project created successfully.', 'id' => $id]);
                else echo json_encode(['success' => false, 'message' => 'Unable to create project.']);
                return;
            }

            if (!empty($return_to)) {
                redirect($return_to);
                return;
            }
            $embedded = $this->input->get('embedded') || $this->input->post('embedded');
            $return = 'projects' . ($embedded ? '?embedded=1' : '');
            redirect($return);
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
            $ok = $this->Project_model->update($id, $payload);
            if ($ok) {
                $this->session->set_flashdata('success', 'Project updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Unable to update project.');
            }
            // If this was an AJAX request, return JSON so the frontend can update without reload
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                $project = $this->Project_model->get($id);
                if ($ok) echo json_encode(['success' => true, 'message' => 'Project updated successfully.', 'project' => $project]);
                else echo json_encode(['success' => false, 'message' => 'Unable to update project.']);
                return;
            }

            $return = 'projects' . ($this->input->get('embedded') ? '?embedded=1' : '');
            redirect($return);
        }
        $data['project'] = $this->Project_model->get($id);
        $this->load->view('project_form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->require_login()) return;
        if ($id) {
            $ok = $this->Project_model->delete($id);
            if ($ok) {
                $this->session->set_flashdata('success', 'Project deleted.');
            } else {
                $this->session->set_flashdata('error', 'Project could not be deleted.');
            }
        }
        // If this is an AJAX request, respond with JSON so the frontend can update without reload
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            if (isset($ok) && $ok) echo json_encode(['success' => true, 'message' => 'Project deleted.', 'id' => $id]);
            else echo json_encode(['success' => false, 'message' => 'Project could not be deleted.']);
            return;
        }

        $return = 'projects' . ($this->input->get('embedded') ? '?embedded=1' : '');
        redirect($return);
    }
}

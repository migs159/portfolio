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

    public function get($id = null)
    {
        if (!$this->session->userdata('logged_in')) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }
        
        if (!$id) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project ID required']);
            return;
        }

        $project = $this->Project_model->get($id);
        if ($project) {
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'project' => $project]);
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
        }
    }

    public function create()
    {
        if (!$this->require_login()) return;
        if ($this->input->method() === 'post') {
            try {
                $payload = $this->input->post();
                // attach current user id when available
                $uid = $this->session->userdata('user_id');
                if ($uid) $payload['user_id'] = $uid;
                
                // Handle featured checkbox (won't be present if unchecked)
                if (!isset($payload['featured'])) {
                    $payload['featured'] = 0;
                } else {
                    // Ensure featured is 0 or 1, nothing else
                    $payload['featured'] = ($payload['featured'] == 1 || $payload['featured'] === true) ? 1 : 0;
                }
                
                // Handle tags (legacy field)
                $payload['tags'] = isset($payload['tags']) ? array_map('trim', explode(',', $payload['tags'])) : [];
                
                // Ensure type is an array; remove empty option
                if (!isset($payload['type'])) {
                    $payload['type'] = [];
                } elseif (is_array($payload['type'])) {
                    $payload['type'] = array_filter($payload['type'], function($v) { return $v !== ''; });
                } else {
                    $payload['type'] = [];
                }
                
                // Check for duplicate project title before attempting upload/insert
                $titleCheck = isset($payload['title']) ? trim($payload['title']) : '';
                if ($titleCheck !== '') {
                    $existing = $this->Project_model->exists_by_title($titleCheck);
                    if ($existing) {
                        if ($this->input->is_ajax_request()) {
                            header('Content-Type: application/json');
                            http_response_code(409);
                            echo json_encode(['success' => false, 'message' => 'A project with that title already exists.']);
                            return;
                        }
                        $this->session->set_flashdata('error', 'A project with that title already exists.');
                        redirect('projects/index?embedded=1');
                        return;
                    }
                }

                // Handle file upload if image file was provided
                if ($_FILES && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadedPath = $this->handleImageUpload($_FILES['image']);
                    if ($uploadedPath) {
                        $payload['image'] = $uploadedPath;
                    } else {
                        // File upload failed
                        if ($this->input->is_ajax_request()) {
                            header('Content-Type: application/json');
                            http_response_code(400);
                            echo json_encode(['success' => false, 'message' => 'Image upload failed. Only PNG and JPG files up to 5MB are allowed.']);
                            return;
                        }
                        $this->session->set_flashdata('error', 'Image upload failed.');
                        redirect('projects/index?embedded=1');
                        return;
                    }
                }
                
                $id = $this->Project_model->create($payload);
                // Allow quick-create form to request returning to the CRUD dashboard
                $return_to = $this->input->post('return_to');
                // If this was an AJAX request, return JSON instead of redirecting and do NOT set session flash
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    if ($id) {
                        $project = $this->Project_model->get($id);
                        http_response_code(200);
                        echo json_encode(['success' => true, 'message' => 'Project created successfully.', 'id' => $id, 'project' => $project]);
                    } else {
                        http_response_code(400);
                        $db_error = $this->db->error();
                        $error_msg = 'Unable to create project';
                        if (!empty($db_error['message'])) {
                            $error_msg .= ': ' . $db_error['message'];
                        }
                        echo json_encode(['success' => false, 'message' => $error_msg]);
                    }
                    return;
                }

                // Non-AJAX: set session flash and redirect as before
                if ($id) {
                    $this->session->set_flashdata('success', 'Project created successfully.');
                } else {
                    $db_error = $this->db->error();
                    $error_msg = 'Unable to create project';
                    if (!empty($db_error['message'])) {
                        $error_msg .= ': ' . $db_error['message'];
                    }
                    $this->session->set_flashdata('error', $error_msg);
                }

                if (!empty($return_to)) {
                    redirect($return_to);
                    return;
                }
                $embedded = $this->input->get('embedded') || $this->input->post('embedded');
                $return = 'projects' . ($embedded ? '?embedded=1' : '');
                redirect($return);
            } catch (Throwable $e) {
                // Catch any PHP errors and return them as JSON for AJAX requests
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
                    return;
                }
                throw $e;
            }
        }
        $this->load->view('project_form');
    }

    protected function handleImageUpload($file)
    {
        // Validate file type
        $allowed = ['image/png', 'image/jpeg'];
        if (!in_array($file['type'], $allowed)) {
            return false;
        }

        // Validate file size (max 5MB)
        $max_size = 5 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            return false;
        }

        // Create upload directory if it doesn't exist
        $upload_dir = FCPATH . 'assets/img/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'project_' . time() . '_' . mt_rand(1000, 9999) . '.' . $file_ext;
        $target_path = $upload_dir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Return relative path for storage in database
            return 'assets/img/uploads/' . $filename;
        }

        return false;
    }

    public function edit($id = null)
    {
        if (!$this->require_login()) return;
        if (!$id) redirect('projects');
        if ($this->input->method() === 'post') {
            $payload = $this->input->post();
            // ensure user_id stays set when editing
            $uid = $this->session->userdata('user_id');
            if ($uid) $payload['user_id'] = $uid;
            $payload['tags'] = isset($payload['tags']) ? array_map('trim', explode(',', $payload['tags'])) : [];
            
            // Handle featured checkbox (won't be present if unchecked)
            if (!isset($payload['featured'])) {
                $payload['featured'] = 0;
            } else {
                // Ensure featured is 0 or 1, nothing else
                $payload['featured'] = ($payload['featured'] == 1 || $payload['featured'] === true) ? 1 : 0;
            }
            
            // Ensure type is an array; remove empty option (same as create)
            if (!isset($payload['type'])) {
                $payload['type'] = [];
            } elseif (is_array($payload['type'])) {
                $payload['type'] = array_filter($payload['type'], function($v) { return $v !== ''; });
            } else {
                $payload['type'] = [];
            }
            
            // Handle image file upload if provided
            if ($_FILES && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadedPath = $this->handleImageUpload($_FILES['image']);
                if ($uploadedPath) {
                    $payload['image'] = $uploadedPath;
                } else {
                    // Upload validation failed
                    if ($this->input->is_ajax_request()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Invalid file or file too large. Only PNG and JPG files up to 5MB are allowed.']);
                        return;
                    }
                    $this->session->set_flashdata('error', 'Invalid file or file too large. Only PNG and JPG files up to 5MB are allowed.');
                    redirect('projects/edit/' . $id);
                    return;
                }
            }
            
            $ok = $this->Project_model->update($id, $payload);
            // If this was an AJAX request, return JSON so the frontend can update without reload
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                $project = $this->Project_model->get($id);
                if ($ok) echo json_encode(['success' => true, 'message' => 'Project updated successfully.', 'project' => $project]);
                else echo json_encode(['success' => false, 'message' => 'Unable to update project.']);
                return;
            }

            // Non-AJAX: set session flash and redirect
            if ($ok) {
                $this->session->set_flashdata('success', 'Project updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Unable to update project.');
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
                // handled below for non-AJAX
            } else {
                // handled below for non-AJAX
            }
        }
        // If this is an AJAX request, respond with JSON so the frontend can update without reload
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            if (isset($ok) && $ok) echo json_encode(['success' => true, 'message' => 'Project deleted.', 'id' => $id]);
            else echo json_encode(['success' => false, 'message' => 'Project could not be deleted.']);
            return;
        }

        // Non-AJAX: set session flash and redirect
        if (isset($ok) && $ok) {
            $this->session->set_flashdata('success', 'Project deleted.');
        } else {
            $this->session->set_flashdata('error', 'Project could not be deleted.');
        }

        $return = 'projects' . ($this->input->get('embedded') ? '?embedded=1' : '');
        redirect($return);
    }
}

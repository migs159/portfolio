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
        $this->load->model('Skill_model');
        $this->load->model('Contact_model');
        
        // Redirect to login if not logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Source - https://stackoverflow.com/q/77147541
// Posted by Shubham Nayak
// Retrieved 2026-02-16, License - CC BY-SA 4.0

$this->output->set_header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inine';");

        // Ensure portfolio columns exist in users table
        $this->_ensure_portfolio_columns();
    }

    /**
     * Ensure portfolio columns exist in the users table
     * Creates them if they don't exist
     */
    private function _ensure_portfolio_columns()
    {
        try {
            $fields = $this->db->list_fields('users');
            $required_columns = ['hero_title', 'hero_subtitle', 'about_content', 'skills', 'phone', 'github_url', 'linkedin_url', 'education_elementary', 'education_high_school', 'education_senior_high', 'education_college', 'education_certification'];
            
            foreach ($required_columns as $col) {
                if (!in_array($col, $fields)) {
                    $add_column = '';
                    switch($col) {
                        case 'skills':
                            $add_column = "ALTER TABLE users ADD COLUMN `{$col}` LONGTEXT DEFAULT NULL";
                            break;
                        case 'phone':
                        case 'github_url':
                        case 'linkedin_url':
                        case 'education_elementary':
                        case 'education_high_school':
                        case 'education_senior_high':
                        case 'education_college':
                        case 'education_certification':
                            $add_column = "ALTER TABLE users ADD COLUMN `{$col}` VARCHAR(255) DEFAULT NULL";
                            break;
                        default:
                            $add_column = "ALTER TABLE users ADD COLUMN `{$col}` VARCHAR(255) DEFAULT NULL";
                    }
                    if ($add_column) {
                        $this->db->query($add_column);
                    }
                }
            }
        } catch (Exception $e) {
            // Silently fail if there are issues with column checking
            // The application can still function without these columns
        }
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
        $data['portfolio'] = [];
        if ($username) {
            $userRow = $this->User_model->get_by_username($username);
            if ($userRow) {
                $data['user'] = $userRow;
                // also make email available in session for backward compatibility
                if (! $this->session->userdata('email') && ! empty($userRow['email'])) {
                    $this->session->set_userdata('email', $userRow['email']);
                }
                
                // Load skills and contacts from separate tables
                $skills_from_db = $this->Skill_model->get_by_user($userRow['id']);
                $contacts_from_db = $this->Contact_model->get_by_user($userRow['id']);
                
                // Prepare portfolio data for CRUD dashboard
                $data['portfolio'] = [
                    'hero_title' => !empty($userRow['hero_title']) ? $userRow['hero_title'] : 'Miguel Andrei del Rosario',
                    'hero_subtitle' => !empty($userRow['hero_subtitle']) ? $userRow['hero_subtitle'] : 'A Web Developer Trainee',
                    'about_content' => !empty($userRow['about_content']) ? $userRow['about_content'] : 'I\'m a motivated Information Technology student passionate about creating innovative web solutions.',
                    'skills' => $skills_from_db,
                    'contacts' => $contacts_from_db,
                    'education_elementary' => !empty($userRow['education_elementary']) ? $userRow['education_elementary'] : '',
                    'education_high_school' => !empty($userRow['education_high_school']) ? $userRow['education_high_school'] : '',
                    'education_senior_high' => !empty($userRow['education_senior_high']) ? $userRow['education_senior_high'] : '',
                    'education_college' => !empty($userRow['education_college']) ? $userRow['education_college'] : '',
                    'education_certification' => !empty($userRow['education_certification']) ? $userRow['education_certification'] : ''
                ];
            }
        }
        $this->load->view('crud_dashboard', $data);
    }

    /**
     * Handle profile image upload
     * Saves image to assets/img/profiles/profile.png
     */
    private function _upload_profile_image($file)
    {
        // Validate file size (max 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'Image size exceeds 5MB limit'];
        }
        
        // Validate file type
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_mime)) {
            return ['success' => false, 'message' => 'Invalid file type. Only images allowed'];
        }
        
        // Define upload directory
        $upload_dir = FCPATH . 'assets/img/profiles/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0755, true);
        }
        
        // Save as profile.png
        $target_file = $upload_dir . 'profile.png';
        
        try {
            // Use move_uploaded_file for security
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Set proper permissions
                @chmod($target_file, 0644);
                return ['success' => true, 'message' => 'Profile image uploaded successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to save image'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error uploading image: ' . $e->getMessage()];
        }
    }

    public function edit_section()
    {
        if (!$this->input->is_ajax_request()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $section = $this->input->post('section');
        $username = $this->session->userdata('username');

        if (!$section || !$username) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            return;
        }

        // Check if this is a single-field update
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        try {
            // Single-field update mode
            if ($field !== null) {
                return $this->_update_single_field($username, $section, $field, $value);
            }

            // Legacy bulk update mode (for backwards compatibility)
            if ($section === 'home') {
                $title = $this->input->post('title');
                $subtitle = $this->input->post('subtitle');
                
                $update_data = [
                    'hero_title' => $title,
                    'hero_subtitle' => $subtitle
                ];
                
                // Handle profile image upload
                if (!empty($_FILES['profile_image']['name'])) {
                    $upload_result = $this->_upload_profile_image($_FILES['profile_image']);
                    if (!$upload_result['success']) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => $upload_result['message']]);
                        return;
                    }
                }
                
                $this->User_model->update_user_profile($username, $update_data);
                
                echo json_encode(['success' => true, 'message' => 'Home section updated']);
            } 
            elseif ($section === 'about') {
                $content = $this->input->post('content');
                
                $this->User_model->update_user_profile($username, [
                    'about_content' => $content
                ]);
                
                echo json_encode(['success' => true, 'message' => 'About section updated']);
            } 
            elseif ($section === 'skills') {
                $skills_json = $this->input->post('skills');
                $skills = json_decode($skills_json, true);
                
                $this->User_model->update_user_profile($username, [
                    'skills' => $skills_json
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Skills updated']);
            } 
            elseif ($section === 'contact') {
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $github = $this->input->post('github');
                $linkedin = $this->input->post('linkedin');
                
                $this->User_model->update_user_profile($username, [
                    'email' => $email,
                    'phone' => $phone,
                    'github_url' => $github,
                    'linkedin_url' => $linkedin
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Contact info updated']);
            } 
            elseif ($section === 'education') {
                $education_elementary = $this->input->post('education_elementary');
                $education_high_school = $this->input->post('education_high_school');
                $education_senior_high = $this->input->post('education_senior_high');
                $education_college = $this->input->post('education_college');
                $education_certification = $this->input->post('education_certification');
                
                $this->User_model->update_user_profile($username, [
                    'education_elementary' => $education_elementary,
                    'education_high_school' => $education_high_school,
                    'education_senior_high' => $education_senior_high,
                    'education_college' => $education_college,
                    'education_certification' => $education_certification
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Education updated']);
            } 
            else {
                echo json_encode(['success' => false, 'message' => 'Unknown section']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle single-field updates
     */
    private function _update_single_field($username, $section, $field, $value)
    {
        // Map of allowed fields per section for security
        $allowed_fields = [
            'home' => ['hero_title', 'hero_subtitle', 'profile_image'],
            'about' => ['about_content', 'education_elementary', 'education_high_school', 'education_senior_high', 'education_college', 'education_certification'],
            'contact' => ['contact'], // Now uses contacts table
            'skills' => ['skill'] // Special handling for skills
        ];

        // Validate section
        if (!isset($allowed_fields[$section])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid section']);
            return;
        }

        // Skills require special handling
        if ($section === 'skills' && $field === 'skill') {
            return $this->_update_single_skill($username);
        }

        // Contacts require special handling (now uses contacts table)
        if ($section === 'contact') {
            return $this->_update_single_contact($username);
        }

        // Profile image requires special handling (file upload or delete)
        if ($section === 'home' && $field === 'profile_image') {
            // Delete mode - empty value means delete the profile image
            if ($value === '' && empty($_FILES['profile_image']['name'])) {
                $profile_path = FCPATH . 'assets/img/profiles/profile.png';
                if (file_exists($profile_path)) {
                    @unlink($profile_path);
                }
                echo json_encode(['success' => true, 'message' => 'Profile image deleted']);
                return;
            }
            
            // Upload mode
            if (!empty($_FILES['profile_image']['name'])) {
                $upload_result = $this->_upload_profile_image($_FILES['profile_image']);
                if ($upload_result['success']) {
                    echo json_encode(['success' => true, 'message' => 'Profile image updated']);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => $upload_result['message']]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No image file provided']);
            }
            return;
        }

        // Validate field is allowed for this section
        if (!in_array($field, $allowed_fields[$section])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid field for this section']);
            return;
        }

        // Update the single field
        $this->User_model->update_user_profile($username, [
            $field => $value
        ]);

        echo json_encode(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated']);
    }

    /**
     * Handle updating, adding, or deleting a single skill using Skill_model
     */
    private function _update_single_skill($username)
    {
        $skill_id = $this->input->post('skill_id');
        $skill_name = $this->input->post('skill_name');
        $new_skill_name = $this->input->post('value');
        $skill_percent = $this->input->post('skill_percent');
        $add_mode = $this->input->post('add_mode') === '1';
        $delete_mode = ($new_skill_name === '' && ($skill_percent === null || $skill_percent === ''));

        // Get current user data
        $user = $this->User_model->get_by_username($username);
        if (!$user) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }

        $user_id = $user['id'];

        // Add mode - add a new skill to the database
        if ($add_mode) {
            if (!$skill_name) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Skill name is required']);
                return;
            }

            // Check if skill already exists
            $existing = $this->Skill_model->get_by_name($user_id, $skill_name);
            if ($existing) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Skill already exists']);
                return;
            }

            // Validate percent
            if ($skill_percent === null || $skill_percent === '' || $skill_percent < 0 || $skill_percent > 100) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid proficiency percentage']);
                return;
            }

            // Add new skill
            $new_id = $this->Skill_model->create([
                'user_id' => $user_id,
                'name' => $skill_name,
                'percent' => (int)$skill_percent
            ]);

            if ($new_id) {
                echo json_encode(['success' => true, 'message' => 'Skill added', 'id' => $new_id]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add skill']);
            }
            return;
        }

        // Need skill_id for update/delete operations
        if (!$skill_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Skill ID is required']);
            return;
        }

        // Delete mode - soft delete the skill
        if ($delete_mode) {
            if ($this->Skill_model->delete($skill_id)) {
                echo json_encode(['success' => true, 'message' => 'Skill deleted']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete skill']);
            }
            return;
        }

        // Update mode - update the skill
        $update_data = [];
        if ($new_skill_name !== null && $new_skill_name !== '') {
            $update_data['name'] = $new_skill_name;
        }
        if ($skill_percent !== null && $skill_percent !== '') {
            $update_data['percent'] = (int)$skill_percent;
        }

        if (empty($update_data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No data to update']);
            return;
        }

        if ($this->Skill_model->update($skill_id, $update_data)) {
            echo json_encode(['success' => true, 'message' => 'Skill updated']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update skill']);
        }
    }

    /**
     * Handle updating, adding, or deleting a contact using Contact_model
     */
    private function _update_single_contact($username)
    {
        $contact_id = $this->input->post('contact_id');
        $contact_type = $this->input->post('contact_type'); // From edit modal dropdown
        if (!$contact_type) {
            $contact_type = $this->input->post('field'); // Fallback for add mode
        }
        $contact_value = $this->input->post('value');
        $add_mode = $this->input->post('add_mode') === '1';
        $delete_mode = ($contact_value === '' && !$add_mode);

        // Get current user data
        $user = $this->User_model->get_by_username($username);
        if (!$user) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }

        $user_id = $user['id'];

        // Add mode - add a new contact to the database
        if ($add_mode) {
            if (!$contact_type) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Contact type is required']);
                return;
            }

            if (!$contact_value) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Contact value is required']);
                return;
            }

            // Add new contact
            $new_id = $this->Contact_model->create([
                'user_id' => $user_id,
                'type' => $contact_type,
                'label' => ucfirst($contact_type),
                'value' => $contact_value
            ]);

            if ($new_id) {
                echo json_encode(['success' => true, 'message' => 'Contact added', 'id' => $new_id]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add contact']);
            }
            return;
        }

        // Need contact_id for update/delete operations
        if (!$contact_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Contact ID is required']);
            return;
        }

        // Delete mode - soft delete the contact
        if ($delete_mode) {
            if ($this->Contact_model->delete($contact_id)) {
                echo json_encode(['success' => true, 'message' => 'Contact deleted']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete contact']);
            }
            return;
        }

        // Update mode - update the contact
        $update_data = [];
        if ($contact_value !== null) {
            $update_data['value'] = $contact_value;
        }
        if ($contact_type) {
            $update_data['type'] = $contact_type;
            $update_data['label'] = ucfirst($contact_type);
        }

        if (empty($update_data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No data to update']);
            return;
        }

        if ($this->Contact_model->update($contact_id, $update_data)) {
            echo json_encode(['success' => true, 'message' => 'Contact updated']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update contact']);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('portfolio');
    }
}

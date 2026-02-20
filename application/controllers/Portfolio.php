<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller {
    public function index()
    {
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Project_model');
        $this->load->model('User_model');
        $this->load->model('Skill_model');
        $this->load->model('Contact_model');
        $data['site_title'] = 'My Portfolio';
        // Load projects - already sorted by featured DESC, then created_at DESC from Project_model
        $data['projects'] = $this->Project_model->get_all();
        
        // Load portfolio data from the first/default user (or logged-in user if available)
        $data['portfolio_data'] = [];
        $username = $this->session->userdata('username');
        
        // If no username in session, try common defaults in order
        if (!$username) {
            $check_users = ['migs', 'migs123'];
            foreach ($check_users as $check) {
                $user_check = $this->User_model->get_by_username($check);
                if ($user_check) {
                    $username = $check;
                    break;
                }
            }
        }
        
        // Fallback to migs123
        if (!$username) {
            $username = 'migs123';
        }
        
        $user = $this->User_model->get_by_username($username);
        if ($user) {
            // Load skills and contacts from separate tables
            $skills_from_db = $this->Skill_model->get_by_user($user['id']);
            $contacts_from_db = $this->Contact_model->get_by_user($user['id']);
            
            $data['portfolio_data'] = [
                'hero_title' => !empty($user['hero_title']) ? $user['hero_title'] : 'Miguel Andrei del Rosario',
                'hero_subtitle' => !empty($user['hero_subtitle']) ? $user['hero_subtitle'] : 'A Web Developer Trainee',
                'about_content' => !empty($user['about_content']) ? $user['about_content'] : 'I\'m a motivated Information Technology student passionate about creating innovative web solutions.',
                'skills' => $skills_from_db,
                'contacts' => $contacts_from_db,
                'education_elementary' => !empty($user['education_elementary']) ? $user['education_elementary'] : '',
                'education_high_school' => !empty($user['education_high_school']) ? $user['education_high_school'] : '',
                'education_senior_high' => !empty($user['education_senior_high']) ? $user['education_senior_high'] : '',
                'education_college' => !empty($user['education_college']) ? $user['education_college'] : '',
                'education_certification' => !empty($user['education_certification']) ? $user['education_certification'] : ''
            ];
        }
        
        $this->load->view('portfolio', $data);
    }

    // Optional: basic contact handler (non-production)
    public function contact()
    {
        $this->load->helper('url');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $message = $this->input->post('message');

        // For demo: simply redisplay success message. Replace with real mail logic.
        $data['contact_success'] = 'Thanks, we received your message.';
        $data['site_title'] = 'My Portfolio';
        $data['projects'] = [];
        $this->load->view('portfolio', $data);
    }
}

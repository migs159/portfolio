<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller {
    public function index()
    {
        $this->load->helper('url');
        $this->load->model('Project_model');
        $data['site_title'] = 'My Portfolio';
        // Load projects from the JSON-backed Project_model. Show newest first so recently created projects appear as featured.
        $projects = $this->Project_model->get_all();
        // Sample fallback projects to always include
        $samples = [
            ['title'=>'AConnect','description'=>'Short description of AConnect','image'=>base_url('assets/img/proj-a.png'),'url'=>'https://github.com/rgbsedano/AConnect','tags'=>['PHP','CI']],
            ['title'=>'CRUD','description'=>'Short description of CRUD','image'=>base_url('assets/img/proj-b.jpg'),'url'=>site_url('auth/login'),'tags'=>['JS','UI']],
        ];

        if (is_array($projects) && count($projects) > 0) {
            // Sort by id (newest first) so recent projects appear first
            usort($projects, function($a, $b){
                $aid = isset($a['id']) ? intval($a['id']) : 0;
                $bid = isset($b['id']) ? intval($b['id']) : 0;
                return $bid <=> $aid;
            });
            // merge created projects with sample projects so samples remain visible
            $data['projects'] = array_merge($projects, $samples);
        } else {
            // no stored projects, show samples
            $data['projects'] = $samples;
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller {
    public function index()
    {
        $this->load->helper('url');
        $this->load->model('Project_model');
        $data['site_title'] = 'My Portfolio';
        // Load projects - already sorted by featured DESC, then created_at DESC from Project_model
        $data['projects'] = $this->Project_model->get_all();
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

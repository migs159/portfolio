<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller {
    public function index()
    {
        $this->load->helper('url');
        $data['site_title'] = 'My Portfolio';
        $data['projects'] = [
            ['title'=>'AConnect','description'=>'Short description of AConnect','image'=>base_url('assets/img/proj-a.png'),'url'=>'https://github.com/rgbsedano/AConnect','tags'=>['PHP','CI']],
            ['title'=>'CRUD','description'=>'Short description of CRUD','image'=>base_url('assets/img/proj-b.png'),'url'=>site_url('auth/login'),'tags'=>['JS','UI']],
        ];
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

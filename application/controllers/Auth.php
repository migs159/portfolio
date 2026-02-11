<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form']);
        $this->load->model('User_model');
    }

    public function login()
    {
        $data = [];
        // show registration success message if redirected after register
        $data['success'] = $this->session->flashdata('register_success');
        if ($this->input->method() === 'post') {
            $u = trim($this->input->post('username'));
            $p = trim($this->input->post('password'));
            if ($this->User_model->verify($u, $p)) {
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('username', $u);
                // Set a flash message so the dashboard can show a toast after redirect
                $this->session->set_flashdata('login_success', 'Signed in successfully.');
                redirect('crud');
            }
            $data['error'] = 'Invalid credentials';
        }
        $this->load->view('login', $data);
    }

    public function register()
    {
        $data = [];
        if ($this->input->method() === 'post') {
            $first = trim($this->input->post('first_name'));
            $last  = trim($this->input->post('last_name'));
            $email = trim($this->input->post('email'));
            $username = trim($this->input->post('username'));
            $password = $this->input->post('password');
            $confirm  = $this->input->post('confirm');

            if ($first === '' || $last === '' || $email === '' || $username === '' || $password === '' || $confirm === '') {
                $data['error'] = 'Please fill in all fields.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address.';
            } elseif ($password !== $confirm) {
                $data['error'] = 'Passwords do not match.';
            } else {
                // Check uniqueness
                if ($this->User_model->get_by_username($username)) {
                    $data['error'] = 'Username is already taken.';
                } elseif ($this->User_model->get_by_email($email)) {
                    $data['error'] = 'Email is already registered.';
                } else {
                    $insert = [
                        'first_name' => $first,
                        'last_name'  => $last,
                        'email'      => $email,
                        'username'   => $username,
                        'password'   => $password,
                    ];
                    $created = $this->User_model->create_user($insert);
                    if ($created) {
                        $this->session->set_flashdata('register_success', 'Account created successfully. You can now sign in.');
                        redirect('auth/login');
                    } else {
                        $data['error'] = 'Unable to create account. Please try again later.';
                    }
                }
            }
        }

        $this->load->view('register', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('portfolio');
    }
}

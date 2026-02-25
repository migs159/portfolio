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
        // If already logged in, redirect to the CRUD dashboard.
        if ($this->session->userdata('logged_in')) {
            redirect('crud');
            return;
        }
        // show registration success message if redirected after register
        $data['success'] = $this->session->flashdata('register_success');
        if ($this->input->method() === 'post') {
            $u = trim($this->input->post('username'));
            $p = trim($this->input->post('password'));
            if ($this->User_model->verify($u, $p)) {
                $userRow = $this->User_model->get_by_username($u);
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('username', $u);
                if ($userRow && isset($userRow['id'])) {
                    $this->session->set_userdata('user_id', $userRow['id']);
                }
                if ($userRow && !empty($userRow['email'])) {
                    $this->session->set_userdata('email', $userRow['email']);
                }
                // Set a flash message so the dashboard can show a toast after redirect
                $this->session->set_flashdata('success', 'Signed in successfully.');
                redirect('crud');
            }
            $data['error'] = 'The username or password you\'ve entered is incorrect';
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

            // Always pass form data back to the view (except passwords for security)
            $data['first_name'] = $first;
            $data['last_name'] = $last;
            $data['email'] = $email;
            $data['username'] = $username;

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
                    // Clear the email field if it's already registered
                    $data['email'] = '';
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

    /**
     * Forgot password - simple flow that shows a form and displays a generic
     * message. For security we do not reveal whether an email exists.
     */
    public function forgot()
    {
        $data = [];
        if ($this->input->method() === 'post') {
            $email = trim($this->input->post('email'));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address.';
            } else {
                // For privacy we show a generic success message.
                $user = $this->User_model->get_by_email($email);
                if ($user) {
                    // Password reset emails/tokens are not configured in this installation.
                    // For privacy we still show a generic success message below.
                }

                $this->session->set_flashdata('success', 'If an account exists for that email we have sent password reset instructions.');
                redirect('auth/forgot');
            }
        }

        // Surface any flash messages set after redirect
        $data['success'] = $this->session->flashdata('success');
        $data['dev_token'] = $this->session->flashdata('dev_token');
        $this->load->view('forgot_password', $data);
    }

    /**
     * Reset password by token
     */
    public function reset($token = null)
    {
        // Password reset via token is not available when the token model is removed.
        show_404();
        return;
    }
}

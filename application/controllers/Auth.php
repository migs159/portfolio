<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form']);
        $this->load->model('User_model');
        $this->load->model('Password_reset_model');
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
                    // create token and attempt to send email
                    $token = $this->Password_reset_model->create_token($email, 60);
                    $reset_link = site_url('auth/reset/' . $token);
                    $subject = 'Password reset instructions';
                    $message = "Hello,\n\nWe received a request to reset your password.\n\nUse this link to reset your password:\n\n" . $reset_link . "\n\nIf you did not request this, ignore this email.\n";
                    $headers = 'From: noreply@' . $_SERVER['SERVER_NAME'] . "\r\n";
                    $mail_ok = false;
                    // Try CodeIgniter Email (SMTP) if configured
                    try {
                        $this->load->library('email');
                        $this->email->clear(true);
                        $this->email->from('noreply@' . $_SERVER['SERVER_NAME'], 'Portfolio');
                        $this->email->to($email);
                        $this->email->subject($subject);
                        $this->email->message($message);
                        $mail_ok = $this->email->send();
                    } catch (Exception $e) {
                        $mail_ok = false;
                    }

                    // If CI Email not available or failed, try PHP mail()
                    if (!$mail_ok && function_exists('mail')) {
                        $mail_ok = mail($email, $subject, $message, $headers);
                    }

                    if (!$mail_ok) {
                        // Mail failed or unavailable: for local/dev provide token via flash (do NOT do this in production)
                        $this->session->set_flashdata('dev_token', $token);
                    }
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
        if (!$token) {
            show_404();
            return;
        }

        $row = $this->Password_reset_model->find_by_token($token);
        if (!$row) {
            $this->session->set_flashdata('error', 'This reset link is invalid or has expired.');
            redirect('auth/forgot');
            return;
        }

        $data = [];
        if ($this->input->method() === 'post') {
            $password = $this->input->post('password');
            $confirm  = $this->input->post('confirm');
            if (empty($password) || empty($confirm)) {
                $data['error'] = 'Please fill in both password fields.';
            } elseif ($password !== $confirm) {
                $data['error'] = 'Passwords do not match.';
            } else {
                // Update user password by email and remove token
                $updated = $this->User_model->update_password_by_email($row['email'], $password);
                $this->Password_reset_model->delete_by_token($token);
                if ($updated) {
                    $this->session->set_flashdata('success', 'Password updated. You may now sign in.');
                    redirect('auth/login');
                    return;
                } else {
                    $data['error'] = 'Unable to update password. Please try again later.';
                }
            }
        }

        $data['token'] = $token;
        $data['error'] = $data['error'] ?? null;
        $this->load->view('reset_password', $data);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Verify credentials against users table.
     * @return bool
     */
    public function verify($username, $password)
    {
        $user = $this->get_by_username($username);
        if (!$user) return false;
        return password_verify($password, $user['password']);
    }

    /**
     * Get user row by username
     */
    public function get_by_username($username)
    {
        return $this->db->where('username', $username)->get('users')->row_array();
    }

    /**
     * Get user row by email
     */
    public function get_by_email($email)
    {
        return $this->db->where('email', $email)->get('users')->row_array();
    }

    /**
     * Create a new user. Returns inserted id on success, false on failure.
     */
    public function create_user(array $data)
    {
        $now = date('Y-m-d H:i:s');
        $insert = [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'email'    => $data['email'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'created_date' => $now,
        ];

        if ($this->db->insert('users', $insert)) {
            return $this->db->insert_id();
        }
        return false;
    }
}

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
        $fullname = '';
        if (!empty($data['fullname'])) {
            $fullname = $data['fullname'];
        } else {
            $first = isset($data['first_name']) ? trim($data['first_name']) : '';
            $last  = isset($data['last_name']) ? trim($data['last_name']) : '';
            $fullname = trim($first . ' ' . $last);
        }

        $insert = [
            'username' => isset($data['username']) ? $data['username'] : null,
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'email'    => isset($data['email']) ? $data['email'] : null,
            'fullname' => $fullname,
            'role'     => isset($data['role']) ? $data['role'] : 'user',
            'created_at' => $now,
            'updated_at' => null,
        ];

        if ($this->db->insert('users', $insert)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update user profile data by username
     */
    public function update_user_profile($username, array $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('username', $username)->update('users', $data);
    }

    /**
     * Update a user's password by email address.
     * Returns true on success.
     */
    public function update_password_by_email($email, $new_password)
    {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $data = ['password' => $hashed, 'updated_at' => date('Y-m-d H:i:s')];
        return $this->db->where('email', $email)->update('users', $data);
    }
}

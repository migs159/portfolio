<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skill_model extends CI_Model {
    protected $table = 'skills';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all skills for a user (excluding soft-deleted)
     */
    public function get_by_user($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('deleted_at', NULL)
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Get a single skill by ID
     */
    public function get($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Get skill by name for a user
     */
    public function get_by_name($user_id, $name)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('name', $name)
            ->where('deleted_at', NULL)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Create a new skill
     */
    public function create($data)
    {
        $now = date('Y-m-d H:i:s');
        $insert = [
            'user_id' => isset($data['user_id']) ? (int)$data['user_id'] : null,
            'name' => isset($data['name']) ? $data['name'] : '',
            'percent' => isset($data['percent']) ? (int)$data['percent'] : 0,
            'sort_order' => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
            'created_at' => $now
        ];

        if ($this->db->insert($this->table, $insert)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update a skill
     */
    public function update($id, $data)
    {
        $update = [];
        if (isset($data['name'])) $update['name'] = $data['name'];
        if (isset($data['percent'])) $update['percent'] = (int)$data['percent'];
        if (isset($data['sort_order'])) $update['sort_order'] = (int)$data['sort_order'];
        $update['updated_at'] = date('Y-m-d H:i:s');

        if (empty($update)) return false;

        $this->db->where('id', $id);
        return $this->db->update($this->table, $update);
    }

    /**
     * Soft delete a skill
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}

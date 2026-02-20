<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model {
    protected $table = 'contacts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all contacts for a user (excluding soft-deleted)
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
     * Get a single contact by ID
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
     * Get contact by type for a user
     */
    public function get_by_type($user_id, $type)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('type', $type)
            ->where('deleted_at', NULL)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Create a new contact
     */
    public function create($data)
    {
        $now = date('Y-m-d H:i:s');
        $insert = [
            'user_id' => isset($data['user_id']) ? (int)$data['user_id'] : null,
            'type' => isset($data['type']) ? $data['type'] : '',
            'label' => isset($data['label']) ? $data['label'] : '',
            'value' => isset($data['value']) ? $data['value'] : '',
            'sort_order' => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
            'created_at' => $now
        ];

        if ($this->db->insert($this->table, $insert)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update a contact
     */
    public function update($id, $data)
    {
        $update = [];
        if (isset($data['type'])) $update['type'] = $data['type'];
        if (isset($data['label'])) $update['label'] = $data['label'];
        if (isset($data['value'])) $update['value'] = $data['value'];
        if (isset($data['sort_order'])) $update['sort_order'] = (int)$data['sort_order'];
        $update['updated_at'] = date('Y-m-d H:i:s');

        if (empty($update)) return false;

        $this->db->where('id', $id);
        return $this->db->update($this->table, $update);
    }

    /**
     * Soft delete a contact
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}

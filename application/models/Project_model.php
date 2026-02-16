<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {
    protected $table = 'projects';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    protected function slugify($text)
    {
        $text = preg_replace('~[^\\pL\\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) return 'project-' . time();
        return $text;
    }

    protected function makeUniqueSlug($baseSlug)
    {
        // Check if slug already exists (using get() instead of count_all_results for reliability)
        $query = $this->db->select('id')->where('slug', $baseSlug)->where('deleted_at', NULL)->get($this->table, 1);
        
        if ($query->num_rows() === 0) {
            return $baseSlug;
        }
        
        // Slug exists, append timestamp to make it unique
        return $baseSlug . '-' . time();
    }

    public function get_all()
    {
        // Sort by featured DESC (featured first), then by created_at DESC (newest first)
        return $this->db->where('deleted_at', NULL)->order_by('featured', 'DESC')->order_by('created_at', 'DESC')->get($this->table)->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->where('deleted_at', NULL)->get($this->table)->row_array();
    }

    public function create($payload)
    {
        $now = date('Y-m-d H:i:s');
        $data = [];
        $data['user_id'] = isset($payload['user_id']) ? intval($payload['user_id']) : null;
        $data['title'] = isset($payload['title']) ? $payload['title'] : null;
        
        // Generate slug and ensure it's unique
        $baseSlug = isset($payload['slug']) && $payload['slug'] !== '' ? $payload['slug'] : $this->slugify($data['title'] ?: 'project');
        $data['slug'] = $this->makeUniqueSlug($baseSlug);
        
        $data['description'] = isset($payload['description']) ? $payload['description'] : null;
        $data['image'] = isset($payload['image']) ? $payload['image'] : null;
        $data['url'] = isset($payload['url']) ? $payload['url'] : null;
        $data['status'] = isset($payload['status']) ? intval($payload['status']) : 1;
        
        // Explicitly handle featured - must be 0 or 1
        $data['featured'] = (isset($payload['featured']) && ($payload['featured'] == 1 || $payload['featured'] === true)) ? 1 : 0;
        
        // Handle type array (framework/language selections)
        if (isset($payload['type']) && is_array($payload['type'])) {
            $data['type'] = json_encode(array_filter($payload['type']));
        } else {
            $data['type'] = null;
        }
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        try {
            if ($this->db->insert($this->table, $data)) {
                return $this->db->insert_id();
            }
            // Log the database error if insert fails
            log_message('error', 'Project model create failed. DB Error: ' . $this->db->last_query());
            return false;
        } catch (Exception $e) {
            log_message('error', 'Project model create exception: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id, $payload)
    {
        $now = date('Y-m-d H:i:s');
        $data = [];
        if (isset($payload['title'])) $data['title'] = $payload['title'];
        if (isset($payload['slug'])) {
            $baseSlug = $payload['slug'] !== '' ? $payload['slug'] : $this->slugify($payload['title'] ?? 'project');
            // When updating, check for duplicate but exclude current record
            $existing = $this->db->where('slug', $baseSlug)->where('id !=', $id)->where('deleted_at', NULL)->count_all_results($this->table);
            $data['slug'] = ($existing === 0) ? $baseSlug : ($baseSlug . '-' . time());
        }
        // Handle type array (framework/language selections)
        if (isset($payload['type'])) {
            if (is_array($payload['type'])) {
                $data['type'] = json_encode(array_filter($payload['type']));
            } else {
                $data['type'] = $payload['type'];
            }
        }
        if (isset($payload['description'])) $data['description'] = $payload['description'];
        if (isset($payload['image'])) $data['image'] = $payload['image'];
        if (isset($payload['url'])) $data['url'] = $payload['url'];
        if (isset($payload['status'])) $data['status'] = intval($payload['status']);
        
        // Explicitly handle featured - must be 0 or 1, nothing else
        if (isset($payload['featured'])) {
            $data['featured'] = ($payload['featured'] == 1 || $payload['featured'] === true || $payload['featured'] === '1') ? 1 : 0;
        }
        
        if (isset($payload['user_id'])) $data['user_id'] = intval($payload['user_id']);
        $data['updated_at'] = $now;

        if (empty($data)) return false;
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        // Soft delete: mark as deleted instead of removing the record
        $now = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return (bool) $this->db->update($this->table, ['deleted_at' => $now]);
    }
}

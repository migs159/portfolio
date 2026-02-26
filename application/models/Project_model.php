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
        $qb = $this->db->select('id')->where('slug', $baseSlug);
        if ($this->db->field_exists('deleted_at', $this->table)) {
            $qb->where('deleted_at', NULL);
        }
        $query = $qb->get($this->table, 1);
        
        if ($query->num_rows() === 0) {
            return $baseSlug;
        }
        
        // Slug exists, append timestamp to make it unique
        return $baseSlug . '-' . time();
    }

    public function get_all()
    {
        // Sort by featured DESC (featured first), then by created_at DESC (newest first)
        $qb = $this->db;
        if ($this->db->field_exists('deleted_at', $this->table)) {
            $qb = $qb->where('deleted_at', NULL);
        }
        $projects = $qb->order_by('featured', 'DESC')->order_by('created_at', 'DESC')->get($this->table)->result_array();
        
        // Decode the type JSON field for each project
        foreach ($projects as &$project) {
            if (isset($project['type']) && !empty($project['type'])) {
                $decoded = json_decode($project['type'], true);
                $project['type'] = is_array($decoded) ? $decoded : [];
            } else {
                $project['type'] = [];
            }
        }
        
        return $projects;
    }

    public function get($id)
    {
        $qb = $this->db->where('id', $id);
        if ($this->db->field_exists('deleted_at', $this->table)) {
            $qb->where('deleted_at', NULL);
        }
        $project = $qb->get($this->table)->row_array();
        
        // Decode the type JSON field back to an array for use in forms
        if ($project && isset($project['type']) && !empty($project['type'])) {
            $decoded = json_decode($project['type'], true);
            $project['type'] = is_array($decoded) ? $decoded : [];
        } else {
            $project['type'] = [];
        }
        
        return $project;
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

    /**
     * Check whether a non-deleted project with the given title already exists.
     * Returns the project row array if found, or null otherwise.
     */
    public function exists_by_title($title)
    {
        if ($title === null || $title === '') return null;
        $qb = $this->db->where('title', $title);
        if ($this->db->field_exists('deleted_at', $this->table)) {
            $qb->where('deleted_at', NULL);
        }
        $query = $qb->get($this->table, 1);
        return $query->num_rows() ? $query->row_array() : null;
    }

    public function update($id, $payload)
    {
        $now = date('Y-m-d H:i:s');
        $data = [];
        if (isset($payload['title'])) $data['title'] = $payload['title'];
        if (isset($payload['slug'])) {
            $baseSlug = $payload['slug'] !== '' ? $payload['slug'] : $this->slugify($payload['title'] ?? 'project');
            // When updating, check for duplicate but exclude current record
            $qb = $this->db->where('slug', $baseSlug)->where('id !=', $id);
            if ($this->db->field_exists('deleted_at', $this->table)) {
                $qb->where('deleted_at', NULL);
            }
            $existing = $qb->count_all_results($this->table);
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
        if ($this->db->field_exists('deleted_at', $this->table)) {
            $this->db->where('id', $id);
            return (bool) $this->db->update($this->table, ['deleted_at' => $now]);
        }
        // fallback to hard delete
        return (bool) $this->db->where('id', $id)->delete($this->table);
    }
}

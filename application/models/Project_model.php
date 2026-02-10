<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {
    protected $path;
    public function __construct()
    {
        parent::__construct();
        $dataDir = APPPATH . 'data/';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        $this->path = $dataDir . 'projects.json';
        if (!file_exists($this->path)) {
            file_put_contents($this->path, json_encode([]));
        }
    }

    protected function read()
    {
        $json = file_get_contents($this->path);
        $arr = json_decode($json, true);
        return is_array($arr) ? $arr : [];
    }

    protected function write($data)
    {
        file_put_contents($this->path, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    public function get_all()
    {
        return $this->read();
    }

    public function get($id)
    {
        $all = $this->read();
        foreach ($all as $item) {
            if (isset($item['id']) && $item['id'] == $id) return $item;
        }
        return null;
    }

    public function create($payload)
    {
        $all = $this->read();
        $id = time();
        $payload['id'] = $id;
        $all[] = $payload;
        $this->write($all);
        return $id;
    }

    public function update($id, $payload)
    {
        $all = $this->read();
        foreach ($all as &$item) {
            if (isset($item['id']) && $item['id'] == $id) {
                $payload['id'] = $id;
                $item = $payload;
                $this->write($all);
                return true;
            }
        }
        return false;
    }

    public function delete($id)
    {
        $all = $this->read();
        $out = [];
        $deleted = false;
        foreach ($all as $item) {
            if (isset($item['id']) && $item['id'] == $id) { $deleted = true; continue; }
            $out[] = $item;
        }
        if ($deleted) {
            $this->write($out);
        }
        return $deleted;
    }
}

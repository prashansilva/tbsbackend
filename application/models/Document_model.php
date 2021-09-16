<?php

class Document_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_document_by_id($id) {
        $this->db->select('*');
        $this->db->from("documents d");
        $this->db->where("id", $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_document($start) {
        $this->db->select('*');
        $this->db->from('documents');
        $this->db->limit(2, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_document($document) {
        $this->db->set('create_date_time', 'NOW()', FALSE);
        $this->db->set('update_date_time', 'NOW()', FALSE);
        $this->db->insert('documents', $document);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function delete_document($id) {
        $this->db->where('id', $id);
        $this->db->delete('documents');
    }

}

?>
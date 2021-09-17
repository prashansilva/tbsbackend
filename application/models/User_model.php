<?php

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_user_by_id($id) {
        $this->db->select('*');
        $this->db->from("users");
        $this->db->where("id", $id);
        $query = $this->db->get();
        return $query->result();;
    }

    public function get_user_by_code($code) {
        $this->db->select('*');
        $this->db->from("users");
        $this->db->where("code", $code);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_managers() {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("role_code", 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_leaders_by_manager($manager_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("manager_id", $manager_id);
        $this->db->where("role_code", 2);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_coordinators_by_manager($manager_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("manager_id", $manager_id);
        $this->db->where("role_code", 3);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_coordinators_by_leader($line_leader_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("line_leader_id", $line_leader_id);
        $this->db->where("role_code", 3);
        $query = $this->db->get();
        return $query->result();
    }

    public function create_user($user, $manager_id, $line_leader_id) {
        $this->db->set('first_name', $user['first_name']);
        $this->db->set('last_name', $user['last_name']);
        $this->db->set('mobile_number', $user['mobile_number']);
        $this->db->set('location', $user['location']);
        $this->db->set('code', $user['code']);
        $this->db->set('manager_id', $manager_id);
        $this->db->set('line_leader_id', $line_leader_id);
        $this->db->set('role_code', 3);
        $this->db->insert('users');
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function create_leader($user, $manager_id) {
        $this->db->set('first_name', $user['first_name']);
        $this->db->set('last_name', $user['last_name']);
        $this->db->set('mobile_number', $user['mobile_number']);
        $this->db->set('location', $user['location']);
        $this->db->set('code', $user['code']);
        $this->db->set('manager_id', $manager_id);
        $this->db->set('role_code', 2);
        $this->db->insert('users');
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function upgradeCoordinatorToLeader($id) {
        $this->db->set('line_leader_id', null);
        $this->db->set('role_code', 2);
        $this->db->where("id",$id);
        $this->db->update('users');
    }

}

?>
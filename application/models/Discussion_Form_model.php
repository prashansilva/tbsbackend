<?php

class Discussion_Form_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_discussion_forms_by_filter($roleCode, $userId, $startDate, $endDate, $count) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        if($roleCode == '1') {
            $this->db->where("manager_id", $userId);
        }
        else if ($roleCode == '2') {
            $this->db->where("line_leader_id", $userId);
        }
        else {
            $this->db->where("coordinator_id", $userId);
        }
        $this->db->where("create_date >= ",$startDate);
        $this->db->where("create_date <=",$endDate);
        $this->db->limit(10, $count);
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_all_discussion_forms_by_filter_count($roleCode, $userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        if($roleCode == '1') {
            $this->db->where("manager_id", $userId);
        }
        else if ($roleCode == '2') {
            $this->db->where("line_leader_id", $userId);
        }
        else {
            $this->db->where("coordinator_id", $userId);
        }
        $this->db->where("create_date >= ",$startDate);
        $this->db->where("create_date <=",$endDate);
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_discussion_form_by_id($id) {
        $this->db->select('dicussion_forms.*, users.code as manager_code');
        $this->db->from("dicussion_forms");
        $this->db->join('users', 'users.id = dicussion_forms.manager_id', 'left');
        $this->db->where("dicussion_forms.id", $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_discussion_forms($start) {
        $this->db->select('DF.code, DF.discusser ,DF.prospector ,DF.mobile_number , DF.create_date , M.code as manager_id , L.code as line_leader_id, C.code as coordinator_id');
        $this->db->from('dicussion_forms as DF');
        $this->db->join('users as M', 'M.id = DF.manager_id', 'left');
        $this->db->join('users as L', 'L.id = DF.line_leader_id', 'left');
        $this->db->join('users as C', 'C.id = DF.coordinator_id', 'left');
        $this->db->limit(10, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_discussion_forms_by_coordinator($start, $coordinatorCode, $startdate, $enddate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("coordinator_id", $coordinatorCode);
        $this->db->where("create_date >= ",$startdate);
        $this->db->where("create_date <=",$enddate);
        $this->db->limit(10, $start);
        $query = $this->db->get();
        return $query->result();
    }    

    public function insert_discussion_form($discussion) {
        $this->db->set('create_date', 'NOW()', FALSE);
        $this->db->insert('dicussion_forms', $discussion);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function update_discussion_form($id, $data) {
        $this->db->set('code', $data);
        $this->db->where('id', $id);
        $this->db->update('dicussion_forms');
    }

    public function delete_discussion_form($id) {
        $this->db->where('id', $id);
        $this->db->delete('dicussion_forms');
    }

    public function get_discussion_form_count_by_manager_by_date($id, $date) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("manager_id", $id);
        $this->db->where("create_date", $date);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_discussion_form_count_by_leader_by_date($id, $date) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("line_leader_id", $id);
        $this->db->where("create_date", $date);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_discussion_form_count_by_coordinator_by_date($id, $date) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("coordinator_id", $id);
        $this->db->where("create_date", $date);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_discussion_form_count_by_manager($id) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("manager_id", $id);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_discussion_form_count_by_leader($id) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("line_leader_id", $id);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_discussion_form_count_by_coordinator($id) {
        $this->db->select('*');
        $this->db->from("dicussion_forms");
        $this->db->where("coordinator_id", $id);       
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        return $rowcount;
    }

    public function get_all_discussion_forms_by_filter_manager($userId, $startDate, $endDate, $count) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("manager_id", $userId);
        $this->db->where("line_leader_id", 0);
        $this->db->where("coordinator_id", 0);
        $this->db->where("create_date >= ",$startDate);
        $this->db->where("create_date <=",$endDate);
        $this->db->limit(10, $count);
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_all_discussion_forms_by_filter_count_manager($userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("manager_id", $userId);
        $this->db->where("line_leader_id", 0);
        $this->db->where("coordinator_id", 0);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $query = $this->db->get();
        return $query->result();
    }  

    public function get_all_others_discussion_forms_by_filter_count_manager($userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("manager_id", $userId);
        $this->db->where("line_leader_id > ", 0);
        $this->db->where("coordinator_id >=", 0);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $query = $this->db->get();
        return $query->result();
    }  
    
    public function get_all_discussion_forms_by_filter_lineleade($userId, $startDate, $endDate, $count) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("line_leader_id", $userId);
        $this->db->where("coordinator_id", 0);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $this->db->limit(10, $count);
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_all_discussion_forms_by_filter_count_lineleade($userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("line_leader_id", $userId);
        $this->db->where("coordinator_id", 0);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $query = $this->db->get();
        return $query->result();
    }  

    public function get_all_other_discussion_forms_by_filter_count_lineleade($userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("line_leader_id", $userId);
        $this->db->where("coordinator_id >", 0);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $query = $this->db->get();
        return $query->result();
    }  

    public function get_all_discussion_forms_by_filter_coordinator($userId, $startDate, $endDate, $count) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("coordinator_id", $userId);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $this->db->limit(10, $count);
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_all_discussion_forms_by_filter_count_coordinator($userId, $startDate, $endDate) {
        $this->db->select('*');
        $this->db->from('dicussion_forms');
        $this->db->where("coordinator_id", $userId);
        if($startDate != null) {
            $this->db->where("create_date >= ",$startDate);
        }
        if($endDate != null) {
            $this->db->where("create_date <=",$endDate);
        }
        $query = $this->db->get();
        return $query->result();
    }  

}

?>
<?php
 class Authentication_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function get_user_by_usercode($usercode) {
        $this->db->select("*");
        $this->db->where("user_code",$usercode);
        $this->db->from("authentication");
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_authentication($user_id, $user_code, $password) {
        $this->db->set('user_id', $user_id);
        $this->db->set('user_code', $user_code);
        $this->db->set('password', md5($password));
        $this->db->insert('authentication');
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function update_otp_authentication($id, $otp) {
        $auth = array('otp' => md5($otp));   
        $this->db->where("id",$id);
        $this->db->update('authentication', $auth);
    }

    public function changepassword_authentication($id, $password) {
        $this->db->set('otp', null);
        $this->db->set('password', md5($password));
        $this->db->where("id",$id);
        $this->db->update('authentication');
    }
}
?>
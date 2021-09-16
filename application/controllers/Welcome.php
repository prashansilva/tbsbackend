<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'captcha'));
		$this->load->model(array("Authentication_model"));
        $this->load->model(array("User_model"));
    }

	public function index() {
        if (isset($_GET['id'])) {
            $leaveid = $_GET['id'];
            $data['id'] = $leaveid;
        } else {
            $data['id'] = '';
        }
        $session_data = $this->session->all_userdata();
        if (isset($session_data['session_userdetails_userrole'])) {
            $userrole = $session_data['session_userdetails_userrole'];
        } else {
            $userrole = '';
        }
        if ($userrole == 'SUPERADMIN') {
            redirect(base_url() . 'index.php/dashboard');
        } 
        $data['error_msg_login'] = $this->session->flashdata('error_msg_login');
        $this->load->view('login/login_view', $data);
    }

	public function validate_user() {

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // $data = $this->Authentication_model->exit_user($email, $password);
		// $email == 'tbsadmin@tbs.com' && $password == '!Tbs#2021'
        if (1==1) {
            $this->session->set_userdata('session_userdetails_usernameheader', 'Super Admin');
            $this->session->set_userdata('session_userdetails_userrole', 'SUPERADMIN');
            redirect(base_url() . 'index.php/dashboard');
        } else {
            if (isset($_GET['id'])) {
                $data['id'] = $_GET['id'];
            } else {
                $data['id'] = '';
            }
            $data['error_login'] = 'INVALIDLOGIN';
            $this->load->view('login/login_view', $data);
        }
    }

    public function logout() {

        $this->session->unset_userdata('session_userdetails_userrole');
        $this->session->unset_userdata('session_userdetails_username');

        $data['success_msg_login'] = "You've been logged out successfully.";
        $data['id'] = '';
        $this->load->view('login/login_view', $data);
    }
}

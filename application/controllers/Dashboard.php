<?php
class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Discussion_Form_model');
        $this->load->model('User_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index()
    {

        $session_data = $this->session->all_userdata();
        if (isset($session_data['session_userdetails_usernameheader'])) {
            $data['userheader'] = $session_data['session_userdetails_usernameheader'];
        } else {
            $data['userheader'] = '';
        }

        if (isset($session_data['session_userdetails_userrole'])) {
            $userrole = $session_data['session_userdetails_userrole'];
        } else {
            $userrole = '';
        }
        if ($userrole === 'SUPERADMIN') {
            $viewData = array();
            // $documentList = $this->Discussion_Form_model->get_all_discussion_forms(0);
            // $index = 0;
            // $asd = array();
            // foreach ($documentList as $document) {
            //     $asd.array_push($document);
            //     $index++;
            // }
            $viewData['documents'] = $this->Discussion_Form_model->get_all_discussion_forms(0);
            $this->load->view('admin/admin_dashboard');
            $this->load->view('admin/admin_topnavigation', $data);
            $this->load->view('admin/admin_body', $viewData);
        } else {
            $this->session->set_flashdata('error_msg_login', "You don't have authorization to access this page!");
            redirect(base_url() . 'index.php/');
        }
    }
}

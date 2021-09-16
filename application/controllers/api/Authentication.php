<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Authentication extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('Authorization_Token');
        $this->load->model(array("Authentication_model"));
        $this->load->model(array("User_model"));
    }

    public function genarate_token()
    {
        return md5(uniqid(rand(), TRUE));
    }

    public function login_post()
    {

        $data = $this->request->body;

        $usercode = $data['user_code'];
        $password = md5($data['password']);

        $userdata = $this->Authentication_model->get_user_by_usercode($usercode);

        try {

            if ($userdata != null) {
                $checkId = $userdata[0]->user_id;
                $checkPassword = $userdata[0]->password;

                if ($checkPassword == $password) {
                    $user = $this->User_model->get_user_by_id($checkId);
                    $this->response(array(
                        "messageCode" => 1001,
                        "message" => "Login Sucess",
                        "auth" => true,
                        "data" => $user
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        "messageCode" => 1002,
                        "auth" => false,
                        "message" => "Usercode or password is not matching",
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                }
            } else {


                if ($usercode == '' || $usercode == null || $password == '' || $password == null) {
                    $this->response(array(
                        "messageCode" => 1002,
                        "message" => "Username and password required",
                        "auth" => false,
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                } else {

                    $this->response(array(
                        "messageCode" => 1002,
                        "message" => "Usercode or password is not matching",
                        "auth" => false,
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                }
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "auth" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    // public function logoutuser_post() {
    //     $data = $this->request->body;
    //     $employee_id = $data['employeeId'];
    //     try {
    //         $employee_token = $data['userToken'];
    //         $user = $this->Employee_model->get_user_token($employee_id, $employee_token);
    //         if ($user != "") {
    //             $this->Employee_model->delete_user_token($employee_id, $employee_token);
    //             $response['messageCode'] = 1001;
    //             $response['data'] = [];
    //         } else {
    //             $response['messageCode'] = 1005;
    //             $response['data'] = [];
    //         }
    //         $this->response($response, REST_Controller::HTTP_OK);
    //     } catch (Exception $e) {
    //         $this->response(null, REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }

    // public function verify_post() {
    //     $header = $this->input->request_headers();
    //     $decodedToken = $this->authorization_token->validateToken($header['Authorization']);
    //     $this->response($decodedToken);
    // }
}

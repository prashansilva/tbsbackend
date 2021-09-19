<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller
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

    public function register_post()
    {

        $data = $this->request->body;

        $userdata = $this->User_model->get_user_by_code($data['code']);
        try {
            if ($userdata != null) {
                $this->response(array(
                    "messageCode" => 1002,
                    "message" => "Usercode Already in use",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            } else if ($data['manager_id'] == null || $data['line_leader_id'] == null) {
                $this->response(array(
                    "messageCode" => 1002,
                    "message" => "Select Manager and Line Leader",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            } else {
                $manager = $this->User_model->get_user_by_code($data['manager_id']);
                if ($manager != null) {
                    $leader = $this->User_model->get_user_by_code($data['line_leader_id']);
                    if ($leader != null && $leader[0]->manager_id == $manager[0]->id) {
                        $user_id = $this->User_model->create_user($data, $manager[0]->id, $leader[0]->id);
                        $this->Authentication_model->insert_authentication($user_id, $data['code'], $data['password']);
                        $this->response(array(
                            "messageCode" => 1001,
                            "message" => "Sucessfully registerd",
                            "status" => true,
                            "data" => []
                        ), REST_Controller::HTTP_OK);
                    } else {
                        $this->response(array(
                            "messageCode" => 1001,
                            "message" => "Manager or Line Leader not found",
                            "status" => false,
                            "data" => []
                        ), REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response(array(
                        "messageCode" => 1001,
                        "message" => "Manager or Line Leader not found",
                        "status" => false,
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                }
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }


    public function updateProfile_post()
    {

        $data = $this->request->body;

        $userdata = $this->User_model->get_user_by_code($data['code']);
        try {
            if ($userdata != null) {

                $temp_file_path = tempnam(sys_get_temp_dir(), 'tempimage');
                file_put_contents($temp_file_path, base64_decode($data['image']));
                $image_info = getimagesize($temp_file_path);
                $_FILES['userfile'] = array(
                    'name' => $userdata[0]->id,
                    'tmp_name' => $temp_file_path,
                    'size' => filesize($temp_file_path),
                    'error' => UPLOAD_ERR_OK,
                    'type' => $image_info['mime'],
                );
                $path = profileImagePath;
                if (!is_dir($path)) {
                    mkdir($path, 0755, TRUE);
                }
                $config = array(
                    'upload_path' => $path,
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE
                );
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('userfile', true)) {
                    $upload_data = $this->upload->data();
                    // $attachment_data = array(
                    //     'PROFILEIMAGENAME' => $upload_data['file_name']
                    // );
                    // $this->leave_model->employee_update($data['employeeId'], $attachment_data);
                } else {
                    $error = array('error' => $this->upload->display_errors());
                }
                $this->response(array(
                    "messageCode" => 1002,
                    "message" => "Usercode Already in use",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            } else {
                 $this->response(array(
                "messageCode" => 1002,
                "message" => "No user found",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function managers_post()
    {
        try {
            $managers = $this->User_model->get_all_managers();
            $this->response(array(
                "messageCode" => 1001,
                "message" => "Sucessfully get managers",
                "status" => true,
                "data" => $managers
            ), REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function leaders_post()
    {
        $data = $this->request->body;
        try {
            $leaders = $this->User_model->get_all_leaders_by_manager($data['manager_id']);
            $this->response(array(
                "messageCode" => 1001,
                "message" => "Sucessfully get leaders",
                "status" => true,
                "data" => $leaders
            ), REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function forgetpassword_post()
    {
        $data = $this->request->body;
        try {
            $userdata = $this->Authentication_model->get_user_by_usercode($data['userCode']);
            if ($userdata != null) {
                $six_digit_random_number = mt_rand(100000, 999999);
                $this->Authentication_model->update_otp_authentication($userdata[0]->id, $six_digit_random_number);
                $user = $this->User_model->get_user_by_id($userdata[0]->user_id);
                $mobile = $user[0]->mobile_number;
                $message = "OTP Code ".$six_digit_random_number;
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://smsserver.textorigins.com/Send_sms?src=CYCLOMAX245&email=info@tbs.lk&pwd=Tbs66556&msg=".$six_digit_random_number."&dst=".$mobile,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);


                $this->response(array(
                    "messageCode" => 1002,
                    "message" => 'OTP code will send to mobile',
                    "status" => true,
                    "data" => $response
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    "messageCode" => 1001,
                    "message" => "Usercode not found",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function validateotp_post()
    {
        $data = $this->request->body;
        $otp = md5($data['code']);
        try {
            $userdata = $this->Authentication_model->get_user_by_usercode($data['userCode']);
            if ($userdata != null) {
                if ($userdata[0]->otp == $otp) {
                    $this->response(array(
                        "messageCode" => 1002,
                        "message" => "OTP code validated",
                        "status" => true,
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->response(array(
                        "messageCode" => 1002,
                        "message" => "Wrong OTP code",
                        "status" => false,
                        "data" => []
                    ), REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(array(
                    "messageCode" => 1001,
                    "message" => "Wrong OTP code",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function changepassword_post()
    {
        $data = $this->request->body;
        try {
            $userdata = $this->Authentication_model->get_user_by_usercode($data['userCode']);
            if ($userdata != null) {
                $this->Authentication_model->changepassword_authentication($userdata[0]->id, $data['password']);
                $this->response(array(
                    "messageCode" => 1002,
                    "message" => "Password reset succesful",
                    "status" => true,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            } else {
                $this->response(array(
                    "messageCode" => 1001,
                    "message" => "Password reset unsuccesful",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            }
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Something went wrong",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }
}

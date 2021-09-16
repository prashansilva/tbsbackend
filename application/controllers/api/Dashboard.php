<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Dashboard extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Document_model');
        $this->load->model('Authentication_model');
        $this->load->model('Discussion_Form_model');
        $this->load->model('User_model');
    }

    //common dashboard api

    public function data_post()
    {
        $data = $this->request->body;
        $userRole = $data['role_code'];
        $userId = $data['id'];
        $date = $data['create_date'];
        try {
            $discussionCountToday = 0;
            $discussionCountTotal = 0;
            $leadersCount = 0;
            $coordinatorsCount = 0;
            if ($userRole == 1) {
                $discussionCountToday =  $this->Discussion_Form_model->get_discussion_form_count_by_manager_by_date($userId, $date);
                $discussionCountTotal =  $this->Discussion_Form_model->get_discussion_form_count_by_manager($userId);
                $leadersCount = count($this->User_model->get_all_leaders_by_manager($userId));
                $coordinatorsCount = count($this->User_model->get_all_coordinators_by_manager($userId));
            } else if ($userRole == 2) {
                $discussionCountToday =  $this->Discussion_Form_model->get_discussion_form_count_by_leader_by_date($userId, $date);
                $discussionCountTotal =  $this->Discussion_Form_model->get_discussion_form_count_by_leader($userId);
                $coordinatorsCount = count($this->User_model->get_all_coordinators_by_leader($userId));;
            } else {
                $discussionCountToday =  $this->Discussion_Form_model->get_discussion_form_count_by_coordinator_by_date($userId, $date);
                $discussionCountTotal =  $this->Discussion_Form_model->get_discussion_form_count_by_coordinator($userId);
            }
            $totalCount =  $discussionCountTotal;
            $todayCount =  $discussionCountToday;
            $responseObject['totalCount'] = $totalCount;
            $responseObject['todayCount'] = $todayCount;
            $responseObject['leadersCount'] = $leadersCount;
            $responseObject['coordinatorsCount'] = $coordinatorsCount;
            $documentObject['totalCount'] = $discussionCountTotal;
            $documentObject['todayCount'] = $discussionCountToday;
            $documentObject['documentName'] = 'Discussion Form';
            $responseObject['documents'] = [$documentObject];
            $response['messageCode'] = 1003;
            $response['message'] = 'Dashboard data load successfull';
            $response['data'] = $responseObject;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Document create unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function document_data_post()
    {
        $data = $this->request->body;
        $userRole = $data['role_code'];
        $userId = $data['id'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $documentType = $data['document_type'];
        $count = $data['count'];
        try {
            
            $documentList =  $this->Discussion_Form_model->get_all_discussion_forms_by_filter($userRole, $userId, $startDate, $endDate, $count);
            $countList =  count($this->Discussion_Form_model->get_all_discussion_forms_by_filter_count($userRole, $userId, $startDate, $endDate));

            $response['messageCode'] = 1003;
            $response['message'] = 'Dashboard data load successfull';
            $response['data'] = $documentList;
            $response['count'] = $countList;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Document create unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }


    //manager dashboard api

    public function manager_post()
    {
        $data = $this->request->body;
        try {
            $leaders = $this->User_model->get_all_leaders_by_manager($data['id']);
            $leadersDetailList = array();
            foreach ($leaders as $leader) {
                $discussionCountToday =  $this->Discussion_Form_model->get_discussion_form_count_by_leader_by_date($leader->id, $data['date']);
                $discussionCountTotal =  $this->Discussion_Form_model->get_discussion_form_count_by_leader($leader->id);
                $leaderObject['details'] = $leader;
                $leaderObject['todayCount'] = $discussionCountToday;
                $leaderObject['totalCount'] = $discussionCountTotal;
                array_push($leadersDetailList, $leaderObject);
            }
            $response['messageCode'] = 1003;
            $response['message'] = 'Leaders fetch successfull';
            $response['data'] = $leadersDetailList;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Leaders fetch unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function leadersByManager_post()
    {
        $data = $this->request->body;
        try {
            $leaders = $this->User_model->get_all_leaders_by_manager($data['id']);
            $response['messageCode'] = 1003;
            $response['message'] = 'Leaders fetch successfull';
            $response['data'] = $leaders;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Leaders fetch unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    public function addLeaderByManager_post()
    {
        $data = $this->request->body;
        $leaderCode = $data['code'];
        $userdata = $this->User_model->get_user_by_code($data['code']);
        try {
            if ($userdata != null) {
                $this->response(array(
                    "messageCode" => 1002,
                    "message" => "Leader already in system",
                    "status" => false,
                    "data" => []
                ), REST_Controller::HTTP_OK);
            }
            else {
                $user_id = $this->User_model->create_leader($data, $data['manager_id']);
                $six_digit_random_number = mt_rand(100000, 999999);
                $password = "TBS".$six_digit_random_number.'L';
                $this->Authentication_model->insert_authentication($user_id, $data['code'], $password);
                $response['messageCode'] = 1003;
                $response['message'] = 'Leader add successfull';
                $response['status'] = true;
                $response['data'] = [];
                $this->response($response, REST_Controller::HTTP_OK);
            }
           
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Leader add unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }

    // line leader dashboard api

    public function leader_post()
    {
        $data = $this->request->body;
        try {
            $coordinators = $this->User_model->get_all_coordinators_by_leader($data['id']);
            $coordinatorDetailList = array();
            foreach ($coordinators as $coordinator) {
                $discussionCountToday =  $this->Discussion_Form_model->get_discussion_form_count_by_coordinator_by_date($coordinator->id, $data['date']);
                $discussionCountTotal =  $this->Discussion_Form_model->get_discussion_form_count_by_coordinator($coordinator->id);
                $coordinatorObject['details'] = $coordinator;
                $coordinatorObject['todayCount'] = $discussionCountToday;
                $coordinatorObject['totalCount'] = $discussionCountTotal;
                array_push($coordinatorDetailList, $coordinatorObject);
            }
            $response['messageCode'] = 1003;
            $response['message'] = 'Coordinators fetch successfull';
            $response['data'] = $coordinatorDetailList;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Coordinators create unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }


    // coordinator dahsboard api 

    public function coordinators_post()
    {
        $data = $this->request->body;
        try {
            $document_id = $this->Discussion_Form_model->insert_discussion_form($data);
            $response['messageCode'] = 1003;
            $response['message'] = 'Document create successfull';
            $response['data'] = [];
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Document create unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }
}

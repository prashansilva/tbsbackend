<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Document extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Document_model');
        $this->load->model('Authentication_model');
        $this->load->model('Discussion_Form_model');
    }

    // Discussion Form Document apis
    
    public function all_post()
    {
        $data = $this->request->body;
        try {
            $documents = $this->Discussion_Form_model->get_all_discussion_forms(0);
            $response['messageCode'] = 1003;
            $response['message'] = 'Document create successfull';
            $response['status'] = true;
            $response['data'] = $documents;
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

    public function discussion_form_create_post()
    {
        $data = $this->request->body;
        try {
            $document_id = $this->Discussion_Form_model->insert_discussion_form($data);
            $document = $this->Discussion_Form_model->get_discussion_form_by_id($document_id);
            $code = $document[0]->manager_code.'/'.sprintf('%04d', $document_id);
            $this->Discussion_Form_model->update_discussion_form($document_id, $code);
            $response['messageCode'] = 1003;
            $response['message'] = 'Document create successfull';
            $response['status'] = true;
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

    public function discussion_form_list_post()
    {
        $data = $this->request->body;
        try {
            $documentList = $this->Discussion_Form_model->get_all_discussion_forms_by_coordinator($data['start'] , $data['coordinator_id'], $data['start_date'],  $data['end_date']);
            $response['messageCode'] = 1003;
            $response['message'] = 'Document fetch successfull';
            $response['status'] = true;
            $response['data'] = $documentList;
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(array(
                "messageCode" => 1002,
                "message" => "Document fetch unsuccessfull",
                "status" => false,
                "data" => []
            ), REST_Controller::HTTP_OK);
        }
    }    

    // Sample Document apis

    public function create_post()
    {
        $data = $this->request->body;
        $header = $this->input->request_headers();
        try {
            $user_id = $header['Authorization'];
            $user = $this->Authentication_model->get_user_by_user_id($user_id);
            if ($user != null  && $user[0]->user_role_code == 4148) {
                $document_id = $this->Document_model->insert_document($data);
                $response['messageCode'] = 1003;
                $response['message'] = 'Document create successfull';
                $response['data'] = [];
            } else {
                $response['messageCode'] = 1000;
                $response['message'] = 'Access-denied';
                $response['data'] = [];
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function documents_post()
    {
        $data = $this->request->body;
        $header = $this->input->get_request_header('Authorization', TRUE);
        try {
            $user_id = $header;
            $user = $this->Authentication_model->get_user_by_user_id($user_id);
            if ($user != null  && $user[0]->user_role_code == 4148) {
                $document_list = $this->Document_model->get_all_document($data['start']);
                $response['messageCode'] = 1004;
                $response['message'] = 'Fetch documents successfull';
                $response['data'] = $document_list;
            } else {
                $response['messageCode'] = 1000;
                $response['message'] = 'Access-denied';
                $response['data'] = [];
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function document_post()
    {
        $data = $this->request->body;
        $header = $this->input->get_request_header('Authorization', TRUE);
        try {
            $user_id = $header;
            $user = $this->Authentication_model->get_user_by_user_id($user_id);
            if ($user != null  && $user[0]->user_role_code == 4148) {
                $document = $this->Document_model->get_document_by_id($data['id']);
                $response['messageCode'] = 1005;
                $response['message'] = 'Fetch document successfull';
                $response['data'] = $document;
            } else {
                $response['messageCode'] = 1000;
                $response['message'] = 'Access-denied';
                $response['data'] = [];
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function delete_post()
    {
        $data = $this->request->body;
        $header = $this->input->get_request_header('Authorization', TRUE);
        try {
            $user_id = $header;
            $user = $this->Authentication_model->get_user_by_user_id($user_id);
            if ($user != null  && $user[0]->user_role_code == 4148) {
                $document = $this->Document_model->delete_document($data['id']);
                $response['messageCode'] = 1005;
                $response['message'] = 'Delete document successfull';
                $response['data'] = $document;
            } else {
                $response['messageCode'] = 1000;
                $response['message'] = 'Access-denied';
                $response['data'] = [];
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}

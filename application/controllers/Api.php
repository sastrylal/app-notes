<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once "ApiBase.php";

class Api extends ApiBase {
    
    function __construct() {
        parent::__construct();
    }

    public function index_get(){
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function tags_get() {
        $form_data = $this->head() + $this->get();
        $this->output_data['tags'] = $this->dbModel->getTags();
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function tag_post() {
        $form_data = $this->head() + $this->post();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('tag_name', 'Tag Name', 'required');
        if(!empty($form_data['tag_name']) && $this->dbModel->isDuplicateTag($form_data['tag_name'])){
            $this->form_validation->add_error("Duplicate tag!", "tag_name");
        }
        if ($this->form_validation->run() !== FALSE) {
            $form_data['tag_id'] = $this->dbModel->addTag([
                "tag_name" => $form_data['tag_name'],
                "is_active" => 1
            ]);
            $this->output_data['tag'] = $this->dbModel->getTagById($form_data['tag_id']);
            $this->output_data['tags'] = $this->dbModel->getTags();
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Tag has been added successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function tag_put() {
        $form_data = $this->head() + $this->post();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('tag_id', 'Tag ID', 'required');
        $this->form_validation->set_rules('tag_name', 'Tag Name', 'required');
        if(!empty($form_data['tag_name']) && $this->dbModel->isDuplicateTag($form_data['tag_name'], $form_data['tag_id'])){
            $this->form_validation->add_error("Duplicate tag!", "tag_name");
        }
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->updateTagById($form_data['tag_id'], [
                "tag_name" => $form_data['tag_name']
            ]);
            $this->output_data['tag'] = $this->dbModel->getTagById($form_data['tag_id']);
            $this->output_data['tags'] = $this->dbModel->getTags();
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Tag has been added successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function tag_delete() {
        $form_data = $this->head() + $this->delete();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('tag_id', 'Tag ID', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->deleteTagById($form_data['tag_id']);
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Your tag has been deleted successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function notes_get() {
        $form_data = $this->head() + $this->get();
        $this->output_data['notes'] = $this->dbModel->getNotes();
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function note_post() {
        $form_data = $this->head() + $this->post();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('note_title', 'Note Title', 'required');
        $this->form_validation->set_rules('note_description', 'Note Decription', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $form_data['note_id'] = $this->dbModel->addNote([
                "note_title" => $form_data['note_title'],
                "note_description" => $form_data['note_description'],
                "is_active" => 1
            ]);
            if(!empty($form_data['note_id']) && !empty($form_data['note_tags'])){
                foreach($form_data['note_tags'] as $tag_id){
                    $this->dbModel->addNoteTag([
                        "note_id" => $form_data['note_id'],
                        "tag_id" => $tag_id
                    ]);
                }
            }
            $this->output_data['note'] = $this->dbModel->getNoteById($form_data['note_id']);
            $this->output_data['data'] = $form_data;
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Note has been added successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function note_put() {
        $form_data = $this->head() + $this->post();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('note_id', 'Note Title', 'required');
        $this->form_validation->set_rules('note_title', 'Note Title', 'required');
        $this->form_validation->set_rules('note_description', 'Note Decription', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->updateNoteById($form_data['note_id'], [
                "note_title" => $form_data['note_title'],
                "note_description" => $form_data['note_description']
            ]);
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Note has been added successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function note_delete() {
        $form_data = $this->head() + $this->delete();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('note_id', 'Note ID', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->deleteNoteById($form_data['note_id']);
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Your note has been deleted successfully!";
        } else {
            $this->output_data['status'] = false;
            $this->output_data['errors'] = $this->form_validation->error_message_array();
            $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        }
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function loginWithOTP_post() {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
        $this->form_validation->set_rules('otp', 'OTP', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $user_token = $this->dbModel->loginWithOTP($this->post('mobile'), $this->post('otp'), $this->post('user_type'));
            if (!empty($user_token['user'])) {
                if($user_token['user']['user_type'] == "ADMIN" && !empty($user_token['company'])){
                    $this->load->model("CompanyModel", "companyModel", TRUE);
                    $apoint = $this->companyModel->getAppointConfig($user_token['company']['company_id']);
                    $user_token['user']['appoint_id'] = $apoint['appoint_id'];
                }
                $this->output_data['user'] = $user_token['user'];
                $this->output_data['token'] = $user_token['token'];
                $this->output_data['status'] = true;
                $this->response($this->output_data, REST_Controller::HTTP_OK);
            } else {
                $this->form_validation->add_error("Please enter valid Mobile or OTP", "mobile");
            }
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function logout_get() {
        $this->isValidUser();
        $form_data = $this->head() + $this->get();
        $this->form_validation->set_data($form_data);
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->logoutWithAccessToken($form_data['access_token']);
            $this->output_data['status'] = true;
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

}
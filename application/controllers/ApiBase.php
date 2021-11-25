<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(APPPATH) . '/vendor/autoload.php';
require APPPATH . 'libraries/REST_Controller.php';

class ApiBase extends REST_Controller {
    
    var $output_data = [];
    var $user = [];
    var $form_data = [];

    function __construct() {
        parent::__construct();
        $this->load->model("DbModel", "dbModel", TRUE);
        $this->load->library('form_validation');
        $this->load->helper("Common");
        
        $this->output_data = [
            "status" => true,
            "message" => "",
            "errors" => [],
            "error" => ""
        ];
    }
    
    // public function validateAccessToken($access_token){
    //     $this->user = $this->dbModel->getUserByAccessToken($access_token);
    //     if(!empty($this->user['user_id'])){
    //         return $this->user;
    //     }else{
    //         $this->form_validation->add_error("Please enter valid Access Token", "access_token");
    //         $this->output_data['status'] = false;
    //         $this->output_data['errors'] = $this->form_validation->error_message_array();
    //         $this->output_data['error'] = !empty($this->output_data['errors'][0])?$this->output_data['errors'][0]:"";
    //         $this->response($this->output_data, REST_Controller::HTTP_UNAUTHORIZED);
    //     }
    // }
    
}
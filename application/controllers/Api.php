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

    public function profile_get() {
        $form_data = $this->head() + $this->get();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('access_token', 'Access Token', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $user = $this->validateAccessToken($form_data["access_token"]);
            $this->output_data['user'] = $user;
            $this->output_data['status'] = true;
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function profile_put() {
        $form_data = $this->head() + $this->put();
        $this->form_validation->set_data($form_data);
        $this->form_validation->set_rules('access_token', 'Access Token', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        if(!empty($pdata['mobile']) && !$this->dbModel->isMobileNumberAvailable($pdata['mobile'])){
            $this->form_validation->add_error("Already register with this mobile number", "mobile");
        }
        if ($this->form_validation->run() !== FALSE) {
            $user = $this->validateAccessToken($form_data["access_token"]);
            $pdata = [];
            $pdata['first_name'] = !empty($form_data['first_name']) ? $form_data['first_name'] : "";
            $pdata['last_name'] = !empty($form_data['last_name']) ? $form_data['last_name'] : "";
            $pdata['gender'] = !empty($form_data['gender']) ? $form_data['gender'] : "";
            if(!empty($form_data['mobile'])) $pdata['mobile'] = $form_data['mobile'];
            $this->dbModel->updateUserById($user['user_id'], $pdata);

            $user = $this->dbModel->getUserById($user['user_id']);
            $this->output_data['user'] = $user;
            $this->output_data['status'] = true;
            $this->output_data['message'] = "Your Profile has been updated!";
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function companyServices_get() {
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('company_id', 'Company ID', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->output_data['services'] = $this->dbModel->getCompanyServicesByCompanyId($this->input->get('company_id'));;
            $this->output_data['status'] = true;
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function letusknow_post() {
        $this->form_data = $this->head() + $this->post();
        $this->form_validation->set_data($this->form_data);
        $this->form_validation->set_rules('category_id', 'Category ID', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->dbModel->addLetusKnow([
                "business_name" => $this->form_data['business_name'],
                "category_id" => $this->form_data['category_id'],
                "location" => $this->form_data['location'],
                "city" => $this->form_data['city'],
                "note" => !empty($this->form_data['note'])?$this->form_data['note']:""
            ]);
            $this->output_data['message'] = "Your request have been sent!";
            $this->output_data['status'] = true;
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->output_data['errors'] = $this->form_validation->error_message_array();
        $this->output_data['error'] = !empty($this->output_data['errors'][0]) ? $this->output_data['errors'][0] : "";
        $this->response($this->output_data, REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function businessCategories_get($parent_id = null) {
        $this->output_data['business_categories'] = $this->dbModel->getBusinessCategoriesList($parent_id);
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function homeBanners_get() {
        $this->output_data['banners'] = $this->dbModel->getHomeBannersList();
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function offers_get() {
        $grid_view_offers = [];
        $offers = $this->dbModel->getOffersList();
        $grid_view = [];
        $nav = 0; $listCount = 3;
        foreach ($offers as $offer){
            if($nav == $listCount){
                $grid_view_offers[]['row'] = $grid_view;
                $grid_view = [];
                $nav = 0;
            }
            $grid_view[] = $offer;
            $nav++;
        }
        $grid_view_offers[]['row'] = $grid_view;
        $this->output_data['offers'] = $offers;
        $this->output_data['grid_view_offers'] = $grid_view_offers;
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }
    
    public function reportingIssuesList_get() {
        $this->output_data['issues'] = $this->dbModel->getReportingIssuesList();
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function countries_get() {
        $this->output_data['countries'] = $this->dbModel->getCountriesList();
        $this->output_data['status'] = true;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

    public function findLatLong_get() {
        $address = $this->get('address');
        if ($address) {
            $url = "https://maps.google.com/maps/api/geocode/json?libraries=places&key=AIzaSyDCrz5rqqKqp6cpBognutJDgWniNPrgshg&address=".urlencode($address);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
            $responseJson = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($responseJson);
            
            $this->output_data['address'] = $response;
            $this->output_data['status'] = true;
            $this->response($this->output_data, REST_Controller::HTTP_OK);
        }
        $this->output_data['status'] = false;
        $this->response($this->output_data, REST_Controller::HTTP_OK);
    }

}
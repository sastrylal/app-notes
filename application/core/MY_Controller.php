<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
require APPPATH . "third_party/MX/Controller.php";
 
class MY_Controller extends MX_Controller {
 
    var $header_data = [];
    
    function __construct() {
        parent::__construct();
        if (version_compare(CI_VERSION, '2.1.0', '<')) {
            $this->load->library('security');
        }
        $this->load->helper("Common");
        $this->load->model("DbModel", "dbModel", true);
    }
    
    public function _member_check(){
        if ($this->session->userdata("member") !== null) {
            $this->header_data['member'] = $this->session->userdata("member");
            $this->member = $this->session->userdata("member");
        }else{
            redirect(base_url()."login/");
        }
    }

    public function _template($page_name, $data = []){
        $data = $data + $this->header_data;
        $this->load->view("header", $data);
        $this->load->view($page_name, $data);
        $this->load->view("footer");
    }

    public function _iframe($page_name, $data = []){
        $data = $data + $this->header_data;
        $this->load->view("iframe_header", $data);
        $this->load->view($page_name, $data);
        $this->load->view("iframe_footer");
    }
 
}

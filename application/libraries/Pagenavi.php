<?php

//include_once(dirname(__FILE__) . '/pagination.class.php');
/**
 * Description of Pagenavi
 *
 * @author sastry.chintaluri
 */
class Pagenavi {

    public $CI;
    public $modelInstance = '';
    public $searchFunction = '';
    public $base_url = '';
    public $per_page = 10;
    public $cur_page = 1;
    public $search_data = array();
    public $links_html = "";
    public $items = array();
    public $sno = 1;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('pagination');
    }

    function process($model, $sfun) {
        $this->modelInstance = $model;
        $this->searchFunction = $sfun;
        
        $url_data = parse_url($this->base_url);
        if (!empty($url_data['query'])) {
            $split_parameters = explode('&', $url_data['query']);
            foreach ($split_parameters as $parm) {
                $final_split = explode('=', $parm);
                if (isset($final_split[0]) && isset($final_split[1])) {
                    $this->search_data[$final_split[0]] = $final_split[1];
                }
            }
        }        
        $config['base_url'] = $url_data['path'] . '?' . http_build_query($this->search_data);
        $config['total_rows'] = call_user_func_array(array($this->modelInstance, $this->searchFunction), array($this->search_data, "CNT")); //$this->modelInstance->{$this->searchFunction}($this->search_data, "CNT");
        $config['per_page'] = $this->per_page;
        //$config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['anchor_class'] = 'page-link';
        $config['query_string_segment'] = "page";
        $this->CI->pagination->initialize($config);
        $this->links_html = $this->CI->pagination->create_links();        
        if($this->CI->pagination->cur_page>1){
            $cur_page = $this->CI->pagination->cur_page;
        } else{
            $cur_page = 1;
        }
        $this->cur_page = $cur_page;
        $this->sno = (($cur_page - 1)*$this->CI->pagination->per_page) +1;
        $this->search_data['sno'] = $this->sno;
        $this->search_data['page'] = $cur_page;
        $this->search_data['last_page'] = (!empty($config['total_rows']))?(ceil($config['total_rows'] / $this->per_page)):0;
        $this->search_data['total_rows'] = $config['total_rows'];
        $this->search_data['offset'] = (($cur_page - 1) * $this->CI->pagination->per_page);
        $this->search_data['limit'] = $this->CI->pagination->per_page;
        $this->items = call_user_func_array(array($this->modelInstance, $this->searchFunction), array($this->search_data)); //$this->modelInstance->$this->searchFunction($search_data);
    }

}

<?php

class MY_Form_validation extends CI_Form_validation {

    function __construct($config = array()) {
        parent::__construct($config);
    }

    function error_array() {
        if (count($this->_error_array) === 0)
            return FALSE;
        else
            return $this->_error_array;
    }
    
    function error_message_array() {
        if (count($this->_error_array) === 0)
            return FALSE;
        else
            return array_values($this->_error_array);
    }
    
    function add_error($message, $field_name = null){
        if($field_name){
            $this->_error_array[$field_name] = $message;
        }
    }

}

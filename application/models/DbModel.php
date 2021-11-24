<?php

class DbModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getActivePolls() {
        $this->db->select("*");
        $this->db->where("is_active", "1");
        $query = $this->db->get("tbl_polls");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    function addPollVote($pdata) {
        $this->db->insert("tbl_poll_votes", $pdata);
        return $this->db->insert_id();
    }
    
    function loginUser($email, $pwd) {
        $this->db->select("*");
        $this->db->where("email", $email);
        $this->db->where("pwd", $pwd);
        $query = $this->db->get("tbl_users");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }
    
    function addUser($pdata) {
        $this->db->set("created_on", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_users", $pdata);
        return $this->db->insert_id();
    }

    function updateUserById($user_id, $pdata) {
        $this->db->where("user_id", $user_id);
        return $this->db->update("tbl_users", $pdata);
    }

    function deleteUserById($user_id) {
        $this->db->where("user_id", $user_id);
        return $this->db->delete("tbl_users");
    }
    
    function getUserById($user_id) {
        $this->db->select("*");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("tbl_users");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    function getCountriesList() {
        $this->db->select("m.*");
        $query = $this->db->get("tbl_countries m");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

}

?>
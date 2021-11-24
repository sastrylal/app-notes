<?php

class MyModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function updateUserById($user_id, $pdata) {
        $this->db->where("user_id", $user_id);
        return $this->db->update("tbl_users", $pdata);
    }

    function deleteUserById($user_id) {
        $this->db->where("user_id", $user_id);
        return $this->db->delete("tbl_users");
    }

}

?>
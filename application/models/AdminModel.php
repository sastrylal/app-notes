<?php

class AdminModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getPollById($poll_id, $question_id = null) {
        $this->db->select("*");
        $this->db->where("poll_id", $poll_id);
        $query = $this->db->get("tbl_polls");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
        return [];
    }

    function searchPollVotes($s = array(), $mode = "DATA") {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("m.*");
        }
        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        $this->db->order_by("m.vote_id DESC");
        $query = $this->db->get("tbl_poll_votes m");
        if ($query->num_rows() > 0) {
            if ($mode == "CNT") {
                $row = $query->row_array();
                return $row['CNT'];
            }
            return $query->result_array();
        }
        return false;
    }

    function searchManagePolls($s = array(), $mode = "DATA") {
        if ($mode == "CNT") {
            $this->db->select("COUNT(1) as CNT");
        } else {
            $this->db->select("m.*, (SELECT COUNT(1) FROM tbl_poll_votes v WHERE v.poll_id=m.poll_id AND v.vote = '1') AS vote_cnt", false);
        }
        if (!empty($s['key'])) {
            $this->db->where("m.poll_title LIKE '%" . $s['key'] . "%' ");
        }
        if (isset($s['limit']) && isset($s['offset'])) {
            $this->db->limit($s['limit'], $s['offset']);
        }
        $this->db->order_by("m.poll_id DESC");
        $query = $this->db->get("tbl_polls m");
        if ($query->num_rows() > 0) {
            if ($mode == "CNT") {
                $row = $query->row_array();
                return $row['CNT'];
            }
            return $query->result_array();
        }
        return false;
    }

    function addPoll($pdata) {
        $this->db->set("created_on", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_polls", $pdata);
        return $this->db->insert_id();
    }

    function updatePollById($poll_id, $pdata) {
        $this->db->where("poll_id", $poll_id);
        return $this->db->update("tbl_polls", $pdata);
    }

    function deletePollById($poll_id) {
        $this->db->where("poll_id", $poll_id);
        return $this->db->delete("tbl_polls");
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
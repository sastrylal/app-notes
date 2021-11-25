<?php

class DbModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getTags() {
        $this->db->select("*");
        $this->db->where("is_active", "1");
        $query = $this->db->get("tbl_tags");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    function getTagById($tag_id) {
        $this->db->select("*");
        $this->db->where("tag_id", $tag_id);
        $query = $this->db->get("tbl_tags");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    function isDuplicateTag($tag_name) {
        $this->db->select("*");
        $this->db->where("tag_name", $tag_name);
        $query = $this->db->get("tbl_tags");
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }
    
    function addTag($pdata) {
        $this->db->set("created_on", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_tags", $pdata);
        return $this->db->insert_id();
    }

    function updateTagById($tag_id, $pdata) {
        $this->db->where("tag_id", $tag_id);
        return $this->db->update("tbl_tags", $pdata);
    }

    function deleteTagById($tag_id) {
        $this->db->where("tag_id", $tag_id);
        return $this->db->delete("tbl_tags");
    }
    
    function getNotes() {
        $this->db->select("*");
        $this->db->where("is_active", "1");
        $query = $this->db->get("tbl_notes");
        if ($query->num_rows() > 0) {
            $rows = $query->result_array();
            foreach($rows as &$row){
                $row['tags'] = $this->getNoteTags($row['note_id']);
            }
            return $rows;
        }
        return [];
    }

    function getNoteById($note_id) {
        $this->db->select("*");
        $this->db->where("note_id", $note_id);
        $query = $this->db->get("tbl_notes");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $row['tags'] = $this->getNoteTags($row['note_id']);
            return $row;
        }
        return [];
    }
    
    function addNote($pdata) {
        $this->db->set("created_on", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_notes", $pdata);
        return $this->db->insert_id();
    }

    function updateNoteById($note_id, $pdata) {
        $this->db->where("note_id", $note_id);
        return $this->db->update("tbl_notes", $pdata);
    }

    function deleteNoteById($note_id) {
        $this->db->where("note_id", $note_id);
        return $this->db->delete("tbl_notes");
    }

    function getNoteTags($note_id) {
        $this->db->select("m.*, t.tag_name");
        $this->db->join("tbl_tags t", "t.tag_id=m.tag_id");
        $this->db->where("m.note_id", $note_id);
        $query = $this->db->get("tbl_note_tags m");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    function addNoteTag($pdata) {
        $this->db->select("m.*");
        $this->db->where("m.note_id", $pdata['note_id']);
        $this->db->where("m.tag_id", $pdata['tag_id']);
        $query = $this->db->get("tbl_note_tags m");
        if ($query->num_rows() > 0) {
            $query->result_array();
        } else {
            $this->db->insert("tbl_note_tags", $pdata);
        }
    }

    function deleteNoteTagsByNoteId($note_id) {
        $this->db->where("note_id", $note_id);
        return $this->db->delete("tbl_note_tags");
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
<?php

class LogModel extends CI_Model
{

    public function newLog($title, $message) {
        $data["title"] = $title;
        $data["message"] = $message;

        $this->db->insert('logs_'.date("Y"), $data);
    }
}
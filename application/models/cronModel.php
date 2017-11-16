<?php

class CronModel extends CI_Model
{

    public function addNewCron() {
        $crons = $this->getCrons();
        $id = end($crons)->id;
        $this->db->insert('cron', array('id' => ($id+1), 'title' => 'New cron job'));
    }
    public function deleteCron($id) {
        $this->db->delete('cron', array('id' => $id));
    }
	public function getCrons()
    {
        $query = $this->db->get('cron');
        $crons = array();
        foreach ($query->result() as $row)
        {
            $crons[$row->id] = $row;
        }
        return $crons;
	}

    public function saveCron($cron)
    {
        $this->db->replace('cron', $cron);
    }

}
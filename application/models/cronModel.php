<?php

class CronModel extends CI_Model
{

    public function addNewCron() {
        $this->db->insert('cron', array('status' => '0'));
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


    public function updateCron($data) {
        $this->load->library('form_validation');
//        foreach($data as $key => $value){
//            $this->form_validation->set_rules($key, $key,'trim|xss_clean');
//        }

//        if($this->form_validation->run()){
            $this->db->replace('cron', $data);
//        }
    }

}
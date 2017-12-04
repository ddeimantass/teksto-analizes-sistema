<?php

class CRUD_model extends CI_Model {

	public function get_records($limit = 0, $offset = 0) {
        $this->db->limit($limit);
        $this->db->offset($offset);
		$query = $this->db->get("user");
		return $query->result();
	}
    public function count_records() {
        $this->db->order_by("id","desc");
		$query = $this->db->get("user");
		return count($query->result());
	}
	public function add_record($data) {

		$this->db->insert("user",$data);
		return;
	}
	public function update_record($senas,$naujas) {

		$this->db->where("email",$senas);
		$this->db->update("user", $naujas);
	}
	public function delete_row() {
		
		$this->db->where("id", $this->uri->segment(3));
		$this->db->delete("user");
	}
}
<?php

class UserModel extends CI_Model {

	public function is_admin() {
        return $this->getRoleByEmail() == 1 ? true : false;
	}
    public function isDeleted() {
        return $this->getDeletedByEmail() == 1 || is_null($this->getDeletedByEmail())  ? true : false;
    }
    
    public function email_exists($email){

		$this->db->where('email',$email);
        $query = $this->db->get('user');

        return $query->num_rows() > 0 ? true : false;

	}
    public function passwordExists($pass){

        $this->db->where('password',md5($pass));
        $query = $this->db->get('user');

        return $query->num_rows() > 0 ? true : false;

    }
	public function can_log_in() {

		$this->db->where('email',$this->input->post('email'));
		$this->db->where('password',md5($this->input->post('password')));

		$query= $this->db->get('user');

        return $query->num_rows() == 1 ? true : false;

	}
    public function add_user() {

        $data = array(
            'email' => $this->input->post('email'),
            'name' => $this->input->post('name'),
            'password' => $this->input->post('password'),
            'role' => '2',
        );

        $query = $this->db->insert('user',$data);

        return $query ? true : false;
    }
    public function updatePassword()
    {
        $user = $this->getUserByEmail($this->session->userdata('email'));
        $data = array(
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role_id' => $user->role_id,
            'deleted' => $user->deleted,
            'password' => $this->input->post('password'),
        );

        $query = $this->db->replace('user', $data);

        return $query ? true : false;
    }

    public function getUsers()
    {
        $query = $this->db->get('user');
        return $query->result();
    }
    public function getUserByEmail($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            return $row;
        }
        return $query->result();
    }
    public function getRoleByEmail() {
        $this->db->where('email',$this->input->post('email'));
        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            return $row->role_id;
        }
        return null;
    }

    public function getAllRoles() {
        $query = $this->db->get('role');
        $roles = array();
        foreach ($query->result() as $row)
        {
            $roles[$row->id] = $row->title;
        }
        return $roles;
    }
    public function getNameByEmail() {
        $this->db->where('email',$this->input->post('email'));
        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            return $row->name;
        }
        return null;
    }
    public function getDeletedByEmail() {
        $this->db->where('email',$this->input->post('email'));
        $query = $this->db->get('user');
        foreach ($query->result() as $row)
        {
            return $row->deleted;
        }
        return null;
    }
}
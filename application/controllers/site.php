<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {
    
    public function index() {
        if($this->session->userdata('role_id') == 1) {
            redirect('admin/main');
        }
        else{
            $this->news();
        }
	}
    public function news() {
        if($this->session->userdata('is_logged_in')){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->view("header");
            $this->load->view("sideBar");
            $this->load->view("news");
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
	}
    public function browse() {
        if($this->session->userdata('is_logged_in')){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->view("header");
            $this->load->view("sideBar");
            $this->load->view("browse");
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
    public function dataTables() {
        if($this->session->userdata('is_logged_in')){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->view("header");
            $this->load->view("sideBar");
            $this->load->view("data");
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
    public function analyse() {
        if($this->session->userdata('is_logged_in')){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->view("header");
            $this->load->view("sideBar");
            $this->load->view("analyse");
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
} 
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function login() {
        $this->load->library('form_validation');
        $this->load->model("userModel");

        $this->form_validation->set_rules('email','Email','required|trim|max_length[50]|valid_email|xss_clean|callback_validate_credentials');
        $this->form_validation->set_rules('password','Password','required|max_length[30]|md5|trim');

        $this->form_validation->set_message('required', '%s field is required');
        $this->form_validation->set_message('max_length', '%s maximum symbol count is %s');
        $this->form_validation->set_message("valid_email", 'Wrong email');

        if($this->form_validation->run()){
            if($this->userModel->isDeleted() === true){
                $data['prierr']= "The user does not exist";
                $this->load->view("logHeader");
                $this->load->view("login",$data);
                $this->load->view("LogFooter");
            }
            else {
                $data = array(
                    'role_id' => $this->userModel->getRoleByEmail(),
                    'name' => $this->userModel->getNameByEmail(),
                    'email' => $this->input->post('email'),
                    'is_logged_in'=>1
                );
                $this->session->set_userdata($data);
                session_write_close();
                if($this->userModel->is_admin()) {
                    redirect('admin/portals');
                }
                else{
                    redirect('site/news');
                }
            }
        }
        else{
            $data['prierr']= validation_errors();
            $this->load->view("logHeader");
            $this->load->view("login",$data);
            $this->load->view("LogFooter");
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('user/login');
    }
    public function change()
    {
        if($this->session->userdata('is_logged_in')) {
            $this->load->library('form_validation');
            $this->load->model('userModel');

            $this->form_validation->set_rules('oldPassword', 'Current password', 'required|callback_validatePass|max_length[30]|md5|trim');
            $this->form_validation->set_rules('password', 'New password', 'required|max_length[30]|md5|trim');
            $this->form_validation->set_rules('cpassword', 'Retype new password', 'required|max_length[30]|md5|trim|matches[password]');

            $this->form_validation->set_message('required', '%s field is required');
            $this->form_validation->set_message('max_length', '%s maximum symbols count is %s');
            $this->form_validation->set_message("matches", 'New passwords mismatch');

            if ($this->form_validation->run()) {
                $this->userModel->updatePassword();
                $data['pass'] = "Your password has been successful updated";
                $this->load->view("header");
                $this->load->view("sideBar");
                $this->load->view("change", $data);
                $this->load->view("footer");
            } else {
                $data['passErr'] = validation_errors();
                $this->load->view("header");
                $this->load->view("sideBar");
                $this->load->view("change", $data);
                $this->load->view("footer");
            }
        }

    }
    public function register() {
        
        $this->load->library('form_validation');
        $this->load->model('userModel');

        $this->form_validation->set_rules('email','Email','required|xss_clean|trim|max_length[50]|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('name','Full name','required|max_length[30]|xss_clean|trim');
        $this->form_validation->set_rules('password','Password','required|max_length[30]|md5|trim');
        $this->form_validation->set_rules('cpassword','Retype password','required|max_length[30]|md5|trim|matches[password]');

        $this->form_validation->set_message('required', '%s field is required');
        $this->form_validation->set_message('max_length', '%s maximum symbol count is %s');
        $this->form_validation->set_message("is_unique", 'User with this email already exists');
        $this->form_validation->set_message("valid_email", 'Wrong email');
        $this->form_validation->set_message("matches", 'Passwords mismatch');

        if($this->form_validation->run()){
            $this->userModel->add_user();
            $data['reg']= "Your registration is successful, please log in";
            $this->load->view("logHeader");
            $this->load->view("login",$data);
            $this->load->view("LogFooter");
        }
        else{
            $data['regerr']= validation_errors();
            $this->load->view("logHeader");
            $this->load->view("register",$data);
            $this->load->view("LogFooter");
        }
    }
    public function forgot() {


        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','required|trim|max_length[50]|valid_email[user.email]|callback_validate_unique');

        $this->form_validation->set_message('required', '%s field is required');
        $this->form_validation->set_message("valid_email", 'Wrong email address');
        $this->form_validation->set_message('max_length', '%s maximum symbol count is %s');

        if($this->form_validation->run()){
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_user' => 'ddeimantass@gmail.com',
                'smtp_pass' => 'Informatikas1',
                'mailtype'  => 'html',
                'charset'   => 'UTF-8',
                'smtp_crypto' => 'tls'
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            $this->email->from('ddeimantass@gmail.com', 'Deimantas');
            $this->email->to($this->input->post('email'));

            $this->email->subject('Email test');

            $passwd = $this->generate_password_suggestion();
            $senas = $this->input->post('email');
            $naujas = array('password' => md5($passwd));
            $this->CRUD_model->update_record($senas,$naujas);

            $this->email->message('Jūsų naujas slaptažodis: '. $passwd );



            if($this->email->send()) {
                $data['reg'] = "Naujas slaptažodis išsiūstas į jūsų el. paštą";
                $this->load->view("LogHeader");
                $this->load->view("login", $data);
                $this->load->view("LogFooter");
            }
            else{
                $data['reg']= "Server error";
                $this->load->view("LogHeader");
                $this->load->view("forgot", $data);
                $this->load->view("LogFooter");
            }
            //echo $this->email->print_debugger();
        }
        else{
            $data['remerr']= validation_errors();
            $this->load->view("LogHeader");
            $this->load->view("forgot",$data);
            $this->load->view("LogFooter");
        }

    }
    public function validate_credentials(){

        $this->load->model("userModel");

        if( $this->userModel->can_log_in()){
            return true;
        }
        else{
            $this->form_validation->set_message("validate_credentials", 'Wrong email or password');
            return false;
        }
    }
    public function validatePass($pass){

        $this->load->model("userModel");
        if($this->userModel->passwordExists($pass))
            return true;
        else{
            $this->form_validation->set_message("validatePass", 'Entered wrong current password');
            return false;
        }
    }
    public function validate_unique($email){

        $this->load->model("userModel");
        if($this->userModel->email_exists($email))
            return true;
        else{
            $this->form_validation->set_message("validate_unique", 'This email does not exist');
            return false;
        }
    }
    function generate_password_suggestion()
    {
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGIHJKLMNPQRSTUVWXYZ123456789_";
        $suggestion = substr(str_shuffle($chars), 0, 12);
        return $suggestion;
    }
}

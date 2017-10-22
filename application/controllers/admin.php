<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function main()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1)
        {
            $this->load->model("templateModel");
            $this->load->model("cronModel");

            if(isset($_POST) && !empty($_POST)){

                if(isset($_POST["add"]) && $_POST["add"] == "template"){

                }
                elseif(isset($_POST["add"]) && $_POST["add"] == "cron"){
                    $this->cronModel->addNewCron();
                }
                elseif(isset($_POST["portal_id"])){
                    $this->checkPortal($_POST);
                }
                elseif(isset($_POST["minute"])){
                    $this->checkCron($_POST);
                }
            }




            $crons = $this->cronModel->getCrons();
            $portals = $this->templateModel->getPortals();

            $portal = isset($_GET["portal"]) ? $_GET["portal"] : '';
            $cron = isset($_GET["cron"]) ? $_GET["cron"] : '';

            $data = array();

            if( !empty($cron) && $crons[$cron]){
                $data['cron'] = $crons[$cron];
            }
            elseif(!empty($portal) && isset($portals[$portal])){
                $template = $this->templateModel->getActiveTemplateById($portals[$portal]->id);
                $data['template'] = isset($template) ? $template : array();
                $data['portal'] = $portals[$portal];
            }
            else{
                $data['crons'] = $crons;
                $data['portals'] = $portals;
            }

            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("main", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
	}
    public function users()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            $this->load->model("userModel");
            $data = array(
                'roles' => $this->userModel->getAllRoles(),
                'users' => $this->userModel->getUsers(),
            );
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("users", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
    public function articles()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->model("templateModel");
            $data = array(
                'articles' => $this->templateModel->getArticles(),
                'portals' =>  $this->templateModel->getPortals(),
                'sources' =>  $this->templateModel->getSources(),
                'authors' =>  $this->templateModel->getAuthors(),
                'categories' => $this->templateModel->getCategories()
            );
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("articles", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }

    public function comments()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->model("templateModel");
            $data = array(
                'portals' =>  $this->templateModel->getPortals(),
                'comments' => $this->templateModel->getComments(),
                'articles' => $this->templateModel->getArticles(),

            );
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("comments", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }

    private function checkPortal($data)
    {
        $this->load->model("templateModel");
        try {
            if(is_numeric($data["id"]) && is_numeric($data["portal_id"]) && is_numeric($data["status"]) && is_numeric($data["category_id"])) {
                $portalData = array("id" => $data["portal_id"], "name" => $data["name"], "logo" => $data["logo"], "archive" => $data["archive"]);
                $this->templateModel->updatePortal($portalData);
                $this->templateModel->updateTemplate($data);
            }
            else {
                if(!is_numeric($data["id"])){
                    throw new Exception('Template id must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["portal_id"])){
                    throw new Exception('Portal id must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["status"])){
                    throw new Exception('Template status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["category_id"])){
                    throw new Exception('Category id must be numeric, last changes are not saved');
                }
            }
        }
        catch (Exception $e) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $e->getMessage(),
                ),
            ));
        }
        die();
    }

    private function checkCron($data)
    {

        die();
    }


} 
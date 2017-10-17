<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function main()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1)
        {

            $this->load->model("templateModel");
            if(isset($_POST) && !empty($_POST)){
                $this->templateModel->updateTemplate($_POST);
                $portalData = array("id" => $_POST["portal_id"], "name" => $_POST["name"], "logo" => $_POST["logo"], "archive" => $_POST["archive"]);
                $this->templateModel->updatePortal($portalData);
                die();
            }

            $portals = $this->templateModel->getAllPortals();

            $portal = isset($_GET["portal"]) ? $_GET["portal"] : '';

            if(!empty($portal)){
                if($portal == "cron"){
                    $this->load->model("cronModel");
                    $cron = $this->cronModel->getCron();
                    $data = array(
                        'cron' => $cron,
                    );
                    $this->load->view("header");
                    $this->load->view("adminSideBar");
                    $this->load->view("main", $data);
                    $this->load->view("footer");
                }
                elseif(isset($portals[$portal])){
                    $template = $this->templateModel->getActiveTemplateById($portals[$portal]->id);
                    $data = array(
                        'template' => isset($template) ? $template : array(),
                        'portal' => $portals[$portal],
                    );
                    $this->load->view("header");
                    $this->load->view("adminSideBar");
                    $this->load->view("main", $data);
                    $this->load->view("footer");
                }
                else{
                    $data = array(
                        'error' => "Wrong data is given",
                        'portals' => $portals,
                    );
                    $this->load->view("header");
                    $this->load->view("adminSideBar");
                    $this->load->view("main", $data);
                    $this->load->view("footer");
                }
            }
            else{
                $data = array(
                    'portals' => $portals,
                );
                $this->load->view("header");
                $this->load->view("adminSideBar");
                $this->load->view("main", $data);
                $this->load->view("footer");
            }
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
} 
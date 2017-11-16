<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function templates()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1)
        {
            $this->load->model("templateModel");

            if(isset($_POST) && !empty($_POST)){

                //var_dump($_POST);

                if(isset($_POST["add"]) ){
                    $this->templateModel->addNewTemplate();
                }
                elseif(isset($_POST["delete"]) ){
                    $this->templateModel->deleteTemplate($_POST["id"]);
                }
                elseif(isset($_POST["id"])){
                    $this->checkTemplate($_POST);
                }
            }

            $templates = $this->templateModel->getAllTemplates();
            $portals = $this->templateModel->getPortals();

            $templateId = isset($_GET["template"]) ? $_GET["template"] : '';

            $data = array();

            if(!empty($templateId) && isset($templates[$templateId])){
                $template = $templates[$templateId];
                $data['template'] = isset($template) ? $template : array();
                $data['portals'] = $portals;
                $data['portal'] = $portals[$template->portal_id];
            }
            else{
                $data['templates'] = $templates;
                $data['portals'] = $portals;
            }

            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("templates", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
	}
    public function portals()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1)
        {
            $this->load->model("templateModel");

            if(isset($_POST) && !empty($_POST)){

                if(isset($_POST["add"])){
                    $this->templateModel->addPortal();
                }
                elseif(isset($_POST["delete"])){
                    $this->templateModel->deletePortal($_POST["id"]);
                }
                elseif(isset($_POST["id"])){
                    $this->checkPortal($_POST);
                }
            }

            $portals = $this->templateModel->getPortals();
            $templates = $this->templateModel->getTemplateIdByPortal();
            $portal = isset($_GET["portal"]) ? $_GET["portal"] : '';

            $data = array();

            if(!empty($portal) && isset($portals[$portal])){
                $data['portal'] = $portals[$portal];
            }
            else{
                $data['portals'] = $portals;
                $data['templates'] = $templates;
            }

            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("portals", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
    public function cron()
    {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){

            $this->load->model("cronModel");
            $data = array();
//                'roles' => $this->userModel->getAllRoles(),
//                'users' => $this->userModel->getUsers(),
//            );
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("cron", $data);
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
            if(is_numeric($data["id"])  && is_numeric($data["status"])) {
                $this->templateModel->updatePortal($data);
            }
            else {
                if(!is_numeric($data["id"])){
                    throw new Exception('Template id must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["status"])){
                    throw new Exception('Template status must be numeric, last changes are not saved');
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
    private function checkTemplate($data)
    {
        $this->load->model("templateModel");
        try {
            if(is_numeric($data["id"]) && is_numeric($data["portal_id"]) && is_numeric($data["status"]) ) {
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

    function checkCron(){

        date_default_timezone_set('Europe/Vilnius');


        if(isset($_GET["json"])){
            $file = APPPATH."../list.json";
            $this->load->library('scraper');
            $json = json_decode(file_get_contents($file), true);

            foreach($json as $key => $cronjob){

                if($cronjob["status"] == "on"){
                    $min = $cronjob["ScrapingInterval"]["minPeriodInSec"];
                    $max = $cronjob["ScrapingInterval"]["maxPeriodInSec"];

                    if(empty($cronjob["lastCheck"])){
                        $json[$key]["lastCheck"] = date("Y-m-d H:i:s");
                        file_put_contents($file, json_encode($json));
                        //$this->scraper->initialize($cronjob["portal"], $min, $max);
                    }
                    else{
                        $nowDate = strtotime(date("Y-m-d H:i:s"));
                        $lastDate = strtotime($cronjob["lastCheck"]);

                        $nowTime = strtotime(date("H:i"));
                        $from = strtotime($cronjob["ScrapingInterval"]["from"]);
                        $to = strtotime($cronjob["ScrapingInterval"]["to"]);

                        $interval = $nowDate - $lastDate;
                        if($interval / 60 >= $cronjob["checkInMin"] && $from < $nowTime && $to > $nowTime){
                            if($cronjob["checkFor"] == "new"){
                                if($cronjob["portal"] == "15min"){
                                    $json[$key]["lastCheck"] = date("Y-m-d H:i:s");
                                    $json[$key]["nextCheck"] = date("Y-m-d H:i:s", (strtotime($cronjob["nextCheck"]) + $cronjob["ScrapingInterval"]["requencyInSeconds"]));
                                    $json[$key]["nextCheck"] = strtotime($cronjob["nextCheck"]) > strtotime(date("Y-m-d H:i:s")) ? date("Y-m-d")." 00:00:00" : $json[$key]["nextCheck"];
                                    file_put_contents($file, json_encode($json));
                                    //$this->scraper->initialize($cronjob["portal"], $min, $max, $cronjob["nextCheck"]);
                                }
                                else{
                                    $json[$key]["lastCheck"] = date("Y-m-d H:i:s");
                                    $json[$key]["nextCheck"] = date("Y-m-d H:i:s");
                                    file_put_contents($file, json_encode($json));
                                    //$this->scraper->initialize($cronjob["portal"], $min, $max, $cronjob["nextCheck"]);
                                }
                            }
                            else{
                                $json[$key]["lastCheck"] = date("Y-m-d H:i:s");
                                $json[$key]["nextCheck"] = date("Y-m-d H:i:s", (strtotime($cronjob["nextCheck"]) - $cronjob["ScrapingInterval"]["requencyInSeconds"]));
                                file_put_contents($file, json_encode($json));
                                //$this->scraper->initialize($cronjob["portal"], $min, $max, $cronjob["nextCheck"]);
                            }
                        }
                    }

                }
            }
        }
    }
} 
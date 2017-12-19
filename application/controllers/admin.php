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
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1)
        {
            $this->load->model("templateModel");
            $this->load->model("cronModel");

            if(isset($_POST) && !empty($_POST)){

                if(isset($_POST["add"])){
                    var_dump($_POST);
                    $this->cronModel->addNewCron();
                }
                elseif(isset($_POST["delete"])){
                    $this->cronModel->deleteCron($_POST["id"]);
                }
                elseif(isset($_POST["id"])){
                    $this->validateCron($_POST);
                }
            }
            $crons = $this->cronModel->getCrons();
            $cron = isset($_GET["cron"]) ? $_GET["cron"] : null;

            $data = array();

            if(!empty($cron) && isset($crons[$cron])){
                $data['cron'] = $crons[$cron];
                $data['portals'] = $this->templateModel->getPortals();
                $data['portal'] = $this->templateModel->getPortal($crons[$cron]->portal_id);
            }
            else{

                foreach($crons as $key => $cron){
                    if($cron->portal_id != 0){
                        $logos[$key] = $this->templateModel->getPortal($cron->portal_id)->logo;
                    }
                }
                $data['crons'] = $crons;
                $data['logos'] = $logos;
            }

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

            if(isset($_POST["deleted"]) ){
                $this->userModel->deletedUser($_POST);
            }

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
    private function validateCron($data)
    {
        $this->load->model("cronModel");
        try {
            if( is_numeric($data["id"])  && is_numeric($data["status"]) && is_numeric($data["portal_id"]) &&
                is_numeric($data["checkFor"]) && is_numeric($data["checkInMin"]) && is_numeric($data["maxPeriodInSec"]) &&
                is_numeric($data["minPeriodInSec"]) && is_numeric($data["frequencyInSeconds"]) &&
                is_numeric($data["ScrapingInterval_weekDayFrom"]) && is_numeric($data["ScrapingInterval_weekDayTo"])
            ){
                $this->cronModel->updateCron($data);
            }
            else {
                if(!is_numeric($data["id"])){
                    throw new Exception('Cron id must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["status"])){
                    throw new Exception('Cron status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["log"])){
                    throw new Exception('Cron log must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["portal_id"])){
                    throw new Exception('Portal id must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["checkFor"])){
                    throw new Exception('CheckFor must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["checkInMin"])){
                    throw new Exception('CheckInMin status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["maxPeriodInSec"])){
                    throw new Exception('MaxPeriodInSec status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["minPeriodInSec"])){
                    throw new Exception('MinPeriodInSec status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["frequencyInSeconds"])){
                    throw new Exception('FrequencyInSeconds status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["ScrapingInterval_weekDayFrom"])){
                    throw new Exception('Weekday from status must be numeric, last changes are not saved');
                }
                else if(!is_numeric($data["ScrapingInterval_weekDayTo"])){
                    throw new Exception('Weekday to must be numeric, last changes are not saved');
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
            $this->load->model("templateModel");
            $this->load->model("cronModel");
            $this->load->library('scraper');

            $json = $this->cronModel->getCrons();

            foreach($json as $key => $cronjob){
                if($cronjob->status == "1"){
                    $min = $cronjob->minPeriodInSec;
                    $max = $cronjob->maxPeriodInSec;

                    if(empty($cronjob->lastCheck)){
                        $data = array(
                            "id" => $key,
                            "lastCheck" => date("Y-m-d H:i:s")
                        );
                        $this->cronModel->updateCron($data);
                        $this->scraper->initialize($cronjob->portal_id, $min, $max);
                    }
                    else{
                        $nowDate = strtotime(date("Y-m-d H:i:s"));
                        $lastDate = strtotime($cronjob->lastCheck);

                        $nowTime = strtotime(date("H:i"));
                        $from = strtotime($cronjob->ScrapingInterval_timeFrom);
                        $to = strtotime($cronjob->ScrapingInterval_timeTo);

                        $interval = $nowDate - $lastDate;
                        if($interval / 60 >= $cronjob->checkInMin && $from < $nowTime && $to > $nowTime && date("w") >= $cronjob->ScrapingInterval_weekDayFrom && date("w") <= $cronjob->ScrapingInterval_weekDayTo){
                            $data = (array) $cronjob;
                            if($cronjob->checkFor == 0){
                                if($this->templateModel->getPortal($cronjob->portal_id)->name == "15min"){

                                    $nextCheck = date("Y-m-d H:i:s", (strtotime($cronjob->nextCheck) + $cronjob->frequencyInSeconds));
                                    $nextCheck = strtotime($cronjob->nextCheck) > strtotime(date("Y-m-d H:i:s")) ? date("Y-m-d")." 00:00:00" : $nextCheck;

                                    $data["lastCheck"] = date("Y-m-d H:i:s");
                                    $data["nextCheck"] = $nextCheck;
                                    $this->cronModel->updateCron($data);
                                    $this->scraper->initialize($cronjob->portal_id, $min, $max, $cronjob->nextCheck);
                                }
                                else{
                                    $data["lastCheck"] = $data["nextCheck"] = date("Y-m-d H:i:s");
                                    $this->cronModel->updateCron($data);
                                    $this->scraper->initialize($cronjob->portal_id, $min, $max, $cronjob->nextCheck);
                                }
                            }
                            else{
                                $data["lastCheck"] = date("Y-m-d H:i:s");
                                $data["nextCheck"] = date("Y-m-d H:i:s", (strtotime($cronjob->nextCheck) - $cronjob->frequencyInSeconds));
                                $this->cronModel->updateCron($data);
                                $this->scraper->initialize($cronjob->portal_id, $min, $max, $cronjob->nextCheck);
                            }
                            break;
                        }
                    }

                }
            }
        }
    }
} 
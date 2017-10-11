<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function main() {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->model("templateModel");
            $templates = $this->templateModel->getAllTemplates();
            $portals = $this->templateModel->getAllPortals();
            $data = array(
                'templates' => $templates,
                'portals' => $portals
            );
            $this->delfi();
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("main", $data);
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
	}
    public function users() {
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
    public function articles() {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->model("templateModel");
            $data = array(
                'articles' => $this->templateModel->getArticles(),
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
    public function comments() {
        if($this->session->userdata('is_logged_in') && $this->session->userdata('role_id') == 1){
            //$data['user_email']= $this->session->userdata('email');
            $this->load->view("header");
            $this->load->view("adminSideBar");
            $this->load->view("comments");
            $this->load->view("footer");
        }
        else{
            redirect('user/login');
        }
    }
    public function delfi() {
        $this->load->model("templateModel");
        $template = $this->templateModel->getDelfiTemplate();
        $portal = $this->templateModel->getPortalByName("delfi");
        include APPPATH . 'third_party/simple_html_dom.php';
        $archive = file_get_html($portal->archive.'fromd='.date("d-m-Y", strtotime(date("d-m-Y") . " - 365 day")).'&tod='.date("d-m-Y").'&channel=0&category=0');
        $articles = array();
        foreach($archive->find($template->article_headline) as $article_headline) {
            foreach($article_headline->find($template->article_link) as $link){
                $linkArr = explode('=',$link->href);
                $id = end($linkArr);
                $articles[$id]['id'] = $id;
                $articles[$id]['link'] = $link->href;
                $articles[$id]['category'] = explode('/',$link->href)[3];
                $articles[$id]['title'] = $link->innertext;
                $articles[$id]['portal_id'] = $this->templateModel->getDelfiTemplate()->portal_id;
            };
            $articles[$id]['image_url'] = $article_headline->find($template->image, 0)->src;
            $articles[$id]['summary'] = $article_headline->find($template->summary, 0)->innertext;

            $shares = $article_headline->find($template->fb_share, 0);
            $articles[$id]['comment_fb'] = isset($shares) ? $shares->innertext : 0;

            $comment_link = $article_headline->find($template->comment_link, 0);
            if(isset($comment_link)){
                $articles[$id]['comment_link'] = $comment_link->href;
                preg_match('#\((.*?)\)#', $comment_link->innertext, $match);
                $articles[$id]['comment_count'] = $match[1];
            }


        }

        $archive->clear();
        unset($archive);

        foreach($articles as $id => $article_attr){
            $article_html = file_get_html($article_attr["link"]);

            $author = $article_html->find($template->article_author, 0);
            $articles[$id]['author'] = isset($author) ? trim($author->plaintext, ",") : null;

            $source = $article_html->find($template->article_source, 0);
            $articles[$id]['source'] = isset($source) ? trim($source->plaintext, ",") : null;

            foreach($article_html->find($template->article_date) as $date){
                $d = DateTime::createFromFormat( 'Y \m\. F j \d\. H:i' , $this->getFormatedDate($date->innertext) );
                $articles[$id]['date'] = $d->format('Y-m-d H:i:s');
            }
            foreach($article_html->find($template->content) as $content){
                $articles[$id]['content'] = isset($articles[$id]['content']) ? $articles[$id]['content']." ".$content->plaintext : $content->plaintext;
            }

            $article_html->clear();
            unset($article_html);

            $this->templateModel->saveArticles($articles);
            break;
            die();
        }
    }
    function getFormatedDate($date){
        $lt_months = array( 'sausio', 'vasario', 'kovo', 'balandžio', 'gegužės', 'birželio', 'liepos', 'rugpjūčio', 'rugsėjo', 'spalio', 'lapkritio', 'gruodžio' );
        $en_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
        return str_replace( $lt_months, $en_months, $date );

    }
} 
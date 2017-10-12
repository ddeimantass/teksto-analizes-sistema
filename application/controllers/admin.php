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
        //$archive = file_get_html($portal->archive.'fromd=02.09.2017&tod=02.09.2017&channel=0&category=0');
        $articles = $comments = array();
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

            $comment_link = $article_headline->find($template->comment_link, 0);
            if(isset($comment_link)){
                $articles[$id]['comment_link'] = $comment_link->href;
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
            if(isset($articles[$id]['comment_link'])){
                $counter = 0;
                do {
                    $comment_html = file_get_html($articles[$id]['link']."&com=1&reg=0&s=2&no=".$counter);
                    $comment_list = $comment_html->find($template->comment, 0);
                    $commentsArr = isset($comment_list) ? $comment_list->children() : array();
                    foreach ($commentsArr as $comment) {
                        if (isset($comment->attr['data-post-id'])) {
                            if($comment->attr['data-post-id']){
                                $cid = $comment->attr['data-post-id'];
                                $comments[$cid]["id"] = $cid;
                                $comments[$cid]["article_id"] = $articles[$id]['id'];
                                $comments[$cid]["portal_id"] = $articles[$id]['portal_id'];
                                $dateIP = explode("IP:", $comment->find($template->comment_date, 0)->plaintext);
                                $d = DateTime::createFromFormat('Y-m-d H:i', trim($dateIP[0]));
                                $comments[$cid]["date"] = $d->format('Y-m-d H:i:s');
                                $comments[$cid]["ip"] = isset($dateIP[1]) ? trim($dateIP[1]) : '';
                                $likes = $comment->find($template->comment_likes, 0);
                                $comments[$cid]["likes"] = isset($likes) ? $likes : 0;
                                $dislikes = $comment->find($template->comment_dislikes, 0);
                                $comments[$cid]["dislikes"] = isset($dislikes) ? $dislikes : 0;

                                $comments[$cid]["content"] = $comment->find($template->comment_content, 0)->plaintext;
                            }
                            $counter++;
                        }
                    }
                    $comment_html->clear();
                    unset($comment_html);
                }
                while($counter % 20 == 0 && $counter != 0);
                $counter = 0;
                do {
                    $comment_html = file_get_html($articles[$id]['link']."&com=1&s=2&no=".$counter);
                    $comment_list = $comment_html->find($template->comment, 0);
                    $commentsArr = isset($comment_list) ? $comment_list->children() : array();
                    foreach ($commentsArr as $comment) {
                        if (isset($comment->attr['data-post-id'])) {
                            if($comment->attr['data-post-id']) {
                                $cid = $comment->attr['data-post-id'];
                                $comments[$cid]["id"] = $cid;
                                $comments[$cid]["article_id"] = $articles[$id]['id'];
                                $comments[$cid]["portal_id"] = $articles[$id]['portal_id'];
                                $dateIP = explode("IP:", $comment->find($template->comment_date, 0)->plaintext);
                                $d = DateTime::createFromFormat('Y-m-d H:i', trim($dateIP[0]));
                                $comments[$cid]["date"] = $d->format('Y-m-d H:i:s');
                                $comments[$cid]["ip"] = isset($dateIP[1]) ? trim($dateIP[1]) : '';
                                $likes = $comment->find($template->comment_likes, 0);
                                $comments[$cid]["likes"] = isset($likes) ? $likes->plaintext : 0;
                                $dislikes = $comment->find($template->comment_dislikes, 0);
                                $comments[$cid]["dislikes"] = isset($dislikes) ? $dislikes->plaintext : 0;

                                $comments[$cid]["content"] = $comment->find($template->comment_content, 0)->plaintext;
                            }
                            $counter++;
                        }
                    }
                    $comment_html->clear();
                    unset($comment_html);
                }
                while($counter % 20 == 0 && $counter != 0);
                $this->templateModel->saveComments($comments);
            }
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
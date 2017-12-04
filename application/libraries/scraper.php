<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scraper
{
    protected $CI, $portal, $template, $date, $min, $max;

    function initialize($portal_id, $min = 10, $max = 25, $date = ""){
        ini_set('max_execution_time', 0);
        date_default_timezone_set('Europe/Vilnius');
        include APPPATH . 'third_party/simple_html_dom.php';
        $this->CI =& get_instance();
        $this->CI->load->model("templateModel");
        $this->CI->load->model("logModel");
        $this->min = $min;
        $this->max = $max;
        $this->date = $date;
        $this->portal = $this->CI->templateModel->getPortal($portal_id);
        $this->template = $this->CI->templateModel->getTemplateByPortal($this->portal->id);
        $this->archive();
    }
    public function archive()
    {
        $stdDateFormat = "Y-m-d H:i:s";
        $date = empty($this->date) ? DateTime::createFromFormat($stdDateFormat, date($stdDateFormat)) : DateTime::createFromFormat($stdDateFormat, $this->date);
        $articles = array();
        if($this->portal->name == "delfi"){
            $maxArchivePage = 1;
            $pageCounter = 1;
            $wait = 10;
            $page = "&page=".$pageCounter;
            $delimeter = "=";

        }
        else{
            $page = "";
            $maxArchivePage = 1;
            $pageCounter = 1;
            $delimeter = "-";
        }


        while ($pageCounter <= $maxArchivePage) {

            if(empty($this->portal->urlDate)){
                $archive = file_get_html($this->portal->archive);
            }
            else{
                $urlDate = explode(" ", trim($this->portal->urlDate));
                $d =  $date->format($this->portal->dateFormat);
                if(count($urlDate) == 1){
                    $archive = file_get_html($this->portal->archive . '?' . $urlDate[0] . '=' . $d . $page);
                }
                else{
                    $archive = file_get_html($this->portal->archive . '?' . $urlDate[0] . '=' . $d . '&' . $urlDate[1] . '=' . $d . $page);
                }
            }

            foreach ($archive->find($this->template->article_headline) as $article_headline) {
                foreach ($article_headline->find($this->template->article_link) as $link) {
                    $linkArr = explode($delimeter, $link->href);

                    $id = end($linkArr);

                    $articles[$id]['id'] = $id;
                    $articles[$id]['link'] = $link->href;
                    $articles[$id]['category'] = explode('/', $link->href)[2 + $this->portal->urlCategory];
                    $articles[$id]['title'] = $link->innertext;
                    $articles[$id]['portal_id'] = $this->portal->id;
                };
                $articles[$id]['date'] = $date->format('Y-m-d H:i:s');
                $articles[$id]['image_url'] = $article_headline->find($this->template->image, 0)->src;

                $comment_link = $article_headline->find($this->template->comment_link, 0);
                $articles[$id]['comment_link'] = isset($comment_link) ? $comment_link->href : null;

            }

            $archive->clear();
            unset($archive);
            ++$pageCounter;
            if(isset($wait)){
                sleep($wait);
            }
        }

        $this->articles($articles);
    }

    public function articles($articles)
    {

        $today = new DateTime(date("Y-m-d"));

        foreach ($articles as $id => $article_attr) {
            ini_set('max_execution_time', 900);
            ini_set('default_socket_timeout', 300);
            $article_html = file_get_html($article_attr["link"]);

            if(isset($this->template->article_author) && !empty($this->template->article_author)){
                $author = $article_html->find($this->template->article_author, 0);
                $articles[$id]['author'] = isset($author) ? trim($author->plaintext, ",") : null;
            }
            if(isset($this->template->article_source) && !empty($this->template->article_source)){
                $source = $article_html->find($this->template->article_source, 0);
                $articles[$id]['source'] = isset($source) ? trim($source->plaintext) : null;
                if(strpos($articles[$id]['source'], "Šaltinis:") !== false){
                    $articles[$id]['source'] = trim(explode(":", $articles[$id]['source'])[1]);
                }
            }
            $summary = $article_html->find($this->template->summary, 0);
            $articles[$id]['summary'] = isset($summary) && !empty($summary) ? trim($article_html->find($this->template->summary, 0)->plaintext) : '';


            foreach ($article_html->find($this->template->content) as $content) {
                $articles[$id]['content'] = isset($articles[$id]['content']) ? $articles[$id]['content'] . " " . $content->plaintext : $content->plaintext;
            }

            $article_html->clear();
            unset($article_html);

            $date = new DateTime($articles[$id]['date']);
            $interval = $today->diff($date);

            if(isset($articles[$id]['comment_link']) && $interval->format("%a") < 30){
                $this->comments($articles[$id]);
                if($this->portal->name == "delfi"){
                    $this->comments($articles[$id], "&reg=0");
                }

            }
            $delay =  rand( $this->min, $this->max);
            sleep($delay);
        }

        $this->CI->templateModel->saveArticles($articles);
        $this->CI->logModel->newLog("Saved articles", count($articles). " articles were successfully saved");
        $this->CI->logModel->newLog("delay", $delay);
        $this->CI->logModel->newLog("delay", serialize($articles));

    }

    public function comments($article, $reg = "")
    {
        $comments = array();
        $counter = 0;
        do {
            ini_set('max_execution_time', 900);
            ini_set('default_socket_timeout', 300);
            if(strpos($article['link'], "?")){
                $comment_html = file_get_html($article['link'] . $reg . "&com=1&s=2&no=" . $counter);
            }
            else{
                $comment_html = file_get_html($article['link'] . "?" . $this->portal->urlComment);
            }

            $comment_list = $comment_html->find($this->template->comment);
            $commentsArr = isset($comment_list) ? $comment_list : array();


            foreach ($commentsArr as $comment) {
                if (isset($comment->attr['data-post-id'])) {
                    if ($comment->attr['data-post-id']) {
                        $id = $comment->attr['data-post-id'];
                        $comments[$id]["id"] = $id;
                        $comments[$id]["article_id"] = $article['id'];
                        $comments[$id]["portal_id"] = $article['portal_id'];
                        $dateIP = explode("IP:", $comment->find($this->template->comment_date, 0)->plaintext);
                        if(isset($dateIP)){
                            $d = DateTime::createFromFormat('Y-m-d H:i', trim($dateIP[0]));
                            $comments[$id]["date"] = $d->format('Y-m-d H:i:s');
                            $comments[$id]["ip"] = isset($dateIP[1]) ? trim($dateIP[1]) : '';
                        }

                        $likes = $comment->find($this->template->comment_likes, 0);
                        $comments[$id]["likes"] = isset($likes->plaintext) ? $likes->plaintext : 0;
                        $dislikes = $comment->find($this->template->comment_dislikes, 0);
                        $comments[$id]["dislikes"] = isset($dislikes->plaintext) ? $dislikes->plaintext : 0;
                        $content = $comment->find($this->template->comment_content, 0);
                        $comments[$id]["content"] = isset($content->plaintext) ? $content->plaintext : 0;
                    }
                    $counter++;
                }
//                if (isset($comment->attr['data-item'])) {
//                    if ($comment->attr['data-item']) {
//                        $id = $comment->attr['data-item'];
//                        $comments[$id]["id"] = $id;
//                        $comments[$id]["article_id"] = $article['id'];
//                        $comments[$id]["portal_id"] = $article['portal_id'];
//
//                        $date = $comment->find($this->template->comment_date, 0);
//                        $comments[$id]["date"] = isset($date->attr["title"]) ? $date->attr["title"] : 0;
//
//                        $ip = $comment->find($this->template->ip, 0);
//                        $comments[$id]["ip"] = isset($ip->attr["title"]) ? $ip->attr["title"] : 0;
//
//                        $likes = $comment->find($this->template->comment_likes, 0);
//                        $comments[$id]["likes"] = isset($likes->plaintext) ? $likes->plaintext : 0;
//
//                        $dislikes = $comment->find($this->template->comment_dislikes, 0);
//                        $comments[$id]["dislikes"] = isset($dislikes->plaintext) ? $dislikes->plaintext : 0;
//
//                        $comments[$id]["content"] = $comment->find($this->template->comment_content, 0)->plaintext;
//                    }
//                    //$counter++;
//                }
            }
            $comment_html->clear();
            unset($comment_html);
        } while ($counter % 20 == 0 && $counter != 0);
        if(!empty($comments)) {
            $this->CI->templateModel->saveComments($comments);
            $this->CI->logModel->newLog("Saved comments", count($comments). " comments were successfully saved");
        }
    }

//    function getFormatedDate($date)
//    {
//        $lt_months = array( 'sausio', 'vasario', 'kovo', 'balandžio', 'gegužės', 'birželio', 'liepos', 'rugpjūčio', 'rugsėjo', 'spalio', 'lapkritio', 'gruodžio' );
//        $en_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
//        return str_replace( $lt_months, $en_months, $date );
//
//    }
} 
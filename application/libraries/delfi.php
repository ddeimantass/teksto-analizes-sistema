<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delfi
{
    protected $CI;

    function __construct(){
        $this->CI =& get_instance();
    }
    public function archive($fromd = '', $tod = '', $maxArchivePage = 1)
    {
        $fromd = empty($fromd) ? date("d-m-Y", strtotime(date("d-m-Y") . " - 365 day")) : $fromd;
        $tod = empty($tod) ? date("d-m-Y") : $tod;
        $articles = array();
        $page = 1;

        $this->CI->load->model("templateModel");
        $template = $this->CI->templateModel->getDelfiTemplate();
        $portal = $this->CI->templateModel->getPortalByName("delfi");

        include APPPATH . 'third_party/simple_html_dom.php';

        while ($page <= $maxArchivePage) {
            $archive = file_get_html($portal->archive . 'fromd=' . $fromd . '&tod=' . $tod . '&channel=0&category=0&page=' . $page);
            //$archive = file_get_html($portal->archive.'fromd=02.09.2017&tod=02.09.2017&channel=0&category=0');


            foreach ($archive->find($template->article_headline) as $article_headline) {
                foreach ($article_headline->find($template->article_link) as $link) {
                    $linkArr = explode('=', $link->href);
                    $id = end($linkArr);
                    $articles[$id]['id'] = $id;
                    $articles[$id]['link'] = $link->href;
                    $articles[$id]['category'] = explode('/', $link->href)[3];
                    $articles[$id]['title'] = $link->innertext;
                    $articles[$id]['portal_id'] = $this->CI->templateModel->getDelfiTemplate()->portal_id;
                };
                $articles[$id]['image_url'] = $article_headline->find($template->image, 0)->src;
                $articles[$id]['summary'] = $article_headline->find($template->summary, 0)->innertext;

                $comment_link = $article_headline->find($template->comment_link, 0);
                if (isset($comment_link)) {
                    $articles[$id]['comment_link'] = $comment_link->href;
                }


            }

            $archive->clear();
            unset($archive);
            ++$page;
        }
        $this->articles($articles);
    }

    public function articles($articles)
    {
        $this->CI->load->model("templateModel");
        $template = $this->CI->templateModel->getDelfiTemplate();

        foreach ($articles as $id => $article_attr) {
            $article_html = file_get_html($article_attr["link"]);

            $author = $article_html->find($template->article_author, 0);
            $articles[$id]['author'] = isset($author) ? trim($author->plaintext, ",") : null;

            $source = $article_html->find($template->article_source, 0);
            $articles[$id]['source'] = isset($source) ? trim($source->plaintext, ",") : null;

            foreach ($article_html->find($template->article_date) as $date) {
                $d = DateTime::createFromFormat('Y \m\. F j \d\. H:i', $this->getFormatedDate($date->innertext));
                $articles[$id]['date'] = $d->format('Y-m-d H:i:s');
            }
            foreach ($article_html->find($template->content) as $content) {
                $articles[$id]['content'] = isset($articles[$id]['content']) ? $articles[$id]['content'] . " " . $content->plaintext : $content->plaintext;
            }

            $article_html->clear();
            unset($article_html);
            if(isset($articles[$id]['comment_link'])){
                $this->comments($articles[$id],'&reg=0');
                $this->comments($articles[$id],'');
            }
            $this->CI->templateModel->saveArticles($articles);
            break;
            die();
        }

    }

    public function comments($article, $reg = '')
    {
        $this->CI->load->model("templateModel");
        $template = $this->CI->templateModel->getDelfiTemplate();

        $comments = array();
        $counter = 0;
        do {
            $comment_html = file_get_html($article['link'] . $reg . "&com=1&s=2&no=" . $counter);
            $comment_list = $comment_html->find($template->comment, 0);
            $commentsArr = isset($comment_list) ? $comment_list->children() : array();
            foreach ($commentsArr as $comment) {
                if (isset($comment->attr['data-post-id'])) {
                    if ($comment->attr['data-post-id']) {
                        $id = $comment->attr['data-post-id'];
                        $comments[$id]["id"] = $id;
                        $comments[$id]["article_id"] = $article['id'];
                        $comments[$id]["portal_id"] = $article['portal_id'];
                        $dateIP = explode("IP:", $comment->find($template->comment_date, 0)->plaintext);
                        $d = DateTime::createFromFormat('Y-m-d H:i', trim($dateIP[0]));
                        $comments[$id]["date"] = $d->format('Y-m-d H:i:s');
                        $comments[$id]["ip"] = isset($dateIP[1]) ? trim($dateIP[1]) : '';
                        $likes = $comment->find($template->comment_likes, 0);
                        $comments[$id]["likes"] = isset($likes) ? $likes : 0;
                        $dislikes = $comment->find($template->comment_dislikes, 0);
                        $comments[$id]["dislikes"] = isset($dislikes) ? $dislikes : 0;

                        $comments[$id]["content"] = $comment->find($template->comment_content, 0)->plaintext;
                    }
                    $counter++;
                }
            }
            $comment_html->clear();
            unset($comment_html);
        } while ($counter % 20 == 0 && $counter != 0);
        if(!empty($comments)) {
            $this->CI->templateModel->saveComments($comments);
        }
    }

    function getFormatedDate($date)
    {
        $lt_months = array( 'sausio', 'vasario', 'kovo', 'balandžio', 'gegužės', 'birželio', 'liepos', 'rugpjūčio', 'rugsėjo', 'spalio', 'lapkritio', 'gruodžio' );
        $en_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
        return str_replace( $lt_months, $en_months, $date );

    }
} 
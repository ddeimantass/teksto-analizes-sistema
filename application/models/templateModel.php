<?php

class TemplateModel extends CI_Model {

    public function addNewTemplate() {
        $templates = $this->getAllTemplates();
        $id = end($templates)->id;
        $this->db->insert('template', array('id' => ($id+1)));
    }

	public function getAllTemplates() {
        $query = $this->db->get('template');
        $templates = array();
        foreach ($query->result() as $row)
        {
            $templates[$row->id] = $row;
        }
        return $templates;
	}
    public function updateTemplate($data) {
        $this->load->library('form_validation');
        foreach($data as $key => $value){
            $this->form_validation->set_rules($key, $key,'trim|xss_clean|max_length[255]');
        }

        if($this->form_validation->run()){
            unset($data["name"]);
            unset($data["archive"]);
            unset($data["logo"]);
            unset($data["date_modified"]);
            $this->db->replace('template', $data);
        }
    }
    public function updatePortal($data) {
        $this->load->library('form_validation');
        foreach($data as $key => $value){
            $this->form_validation->set_rules($key, $key,'trim|xss_clean|max_length[255]');
        }

        if($this->form_validation->run()){
            $this->db->replace('portal', $data);
        }
    }

    public function getActiveTemplateById($id) {
        $this->db->where('portal_id',$id);
        $this->db->where('status',1);
        $query = $this->db->get('template');
        foreach ($query->result() as $row)
        {
            return $row;
        }
    }
    public function getAllPortals() {
        $query = $this->db->get('portal');
        $portals = array();
        foreach ($query->result() as $row)
        {
            $portals[$row->name] = $row;
        }
        return $portals;
    }
    public function getPortals() {
        $query = $this->db->get('portal');
        $portals = array();
        foreach ($query->result() as $row)
        {
            $portals[$row->id] = $row;
        }
        return $portals;
    }
    public function getPortalByName($name) {
        $this->db->where('name',$name);
        $query = $this->db->get('portal');
        foreach ($query->result() as $row)
        {
            return $row;
        }
    }
    public function getDelfiTemplate() {
        $query = $this->db->query('SELECT template.* FROM template LEFT JOIN portal ON portal.id = template.portal_id WHERE portal.name = "delfi"');
        foreach ($query->result() as $row)
        {
            return $row;
        }
    }
    public function saveComments($comments){
        $DBcomments = $this->getComments();
        foreach($comments as $id => $comment){
            if(!isset($DBcomments[$id])) {
                $this->db->insert('comment', $comments[$id]);
            }
            else if(!isset($DBcomments[$id]->content)){
                $this->db->replace('comment', $comments[$id]);
            }
        }
    }
    public function saveArticles($articles){
        $categories = $authors = $sources = array();
        foreach($articles as $id => $article){
            $categories[$article["category"]] = $article["category"];
            if(isset($article["author"])){
                $authors[$article["author"]] = $article["author"];
            }
            if(isset($article["source"])){
                $sources[$article["source"]] = $article["source"];
            }
        }
        $this->saveCategories($categories, end($articles)["portal_id"]);
        $this->saveAuthors($authors);
        $this->saveSources($sources);
        $categories = $this->getCategoriesIdByName();
        $authors = $this->getAuthorsIdByName();
        $sources = $this->getSources();
        $DBarticles = $this->getArticles();
        foreach($articles as $id => $article){
            $articles[$id]["author_id"] = isset($articles[$id]["author"]) ? $authors[$articles[$id]["author"]] : null;
            unset($articles[$id]["author"]);
            $articles[$id]["source_id"] = isset($articles[$id]["source"]) ? $sources[$articles[$id]["source"]] : null;
            unset($articles[$id]["source"]);
            $articles[$id]["category_id"] = $categories[$articles[$id]["category"]];
            unset($articles[$id]["category"]);

            if(!isset($DBarticles[$id])) {
                $this->db->insert('article', $articles[$id]);
            }
            else if(!isset($DBarticles[$id]->content)){
                $this->db->replace('article', $articles[$id]);
            }
        }
    }
    public function getArticles() {
        $query = $this->db->get('article');
        $articles = array();
        foreach ($query->result() as $row)
        {
            $articles[$row->id] = $row;
        }
        return $articles;
    }
    public function getComments() {
        $query = $this->db->get('comment');
        $comments = array();
        foreach ($query->result() as $row)
        {
            $comments[$row->id] = $row;
        }
        return $comments;
    }
    public function saveCategories($categories, $portal_id){
        $DBcategories = $this->getCategoriesIdByName();
        foreach($categories as $category){
            if(!array_key_exists($category, $DBcategories)){
                $this->db->insert('category', array("name" => $category, "portal_id" => $portal_id));
            }
        }
    }
    public function getCategoriesIdByName(){
        $query = $this->db->get('category');
        $categories = array();
        foreach ($query->result() as $row)
        {
            $categories[$row->name] = $row->id;
        }
        return $categories;
    }
    public function getCategories(){
        $query = $this->db->get('category');
        $categories = array();
        foreach ($query->result() as $row)
        {
            $categories[$row->id] = $row;
        }
        return $categories;
    }
    public function saveAuthors($authors){
        $DBauthors = $this->getAuthorsIdByName();
        foreach($authors as $author){
            if(!array_key_exists($author, $DBauthors)){
                $this->db->insert('author', array("name" => $author));
            }
        }

    }
    public function getAuthorsIdByName(){
        $query = $this->db->get('author');
        $authors = array();
        foreach ($query->result() as $row)
        {
            $authors[$row->name] = $row->id;
        }
        return $authors;
    }
    public function getAuthors(){
        $query = $this->db->get('author');
        $authors = array();
        foreach ($query->result() as $row)
        {
            $authors[$row->id] = $row;
        }
        return $authors;
    }
    public function saveSources($sources){
        $DBsources = $this->getSources();
        foreach($sources as $source){
            if(!array_key_exists($source, $DBsources)){
                $this->db->insert('source', array("name" => $source));
            }
        }
    }
    public function getSources(){
        $query = $this->db->get('source');
        $sources = array();
        foreach ($query->result() as $row)
        {
            $sources[$row->name] = $row->id;
        }
        return $sources;
    }
}
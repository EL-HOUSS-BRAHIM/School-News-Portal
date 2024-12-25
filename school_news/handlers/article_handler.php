<?php
// article_handler.php

require_once '../core/Article.php';

class ArticleHandler {
    private $article;

    public function __construct() {
        $this->article = new Article();
    }

    public function createArticle($data) {
        // Validate and sanitize input data
        // Call the Article class method to create an article
        return $this->article->create($data);
    }

    public function readArticle($id) {
        // Call the Article class method to read an article by ID
        return $this->article->read($id);
    }

    public function updateArticle($id, $data) {
        // Validate and sanitize input data
        // Call the Article class method to update an article
        return $this->article->update($id, $data);
    }

    public function deleteArticle($id) {
        // Call the Article class method to delete an article
        return $this->article->delete($id);
    }

    public function listArticles() {
        // Call the Article class method to list all articles
        return $this->article->listAll();
    }
}
?>
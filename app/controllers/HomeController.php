<?php

require_once 'models/PostModel.php';
class HomeController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    public function index() {
        $posts = $this->postModel ->getPosts();

        require_once 'views/home.php';
//        require_once 'views/users/login.php';
    }
}
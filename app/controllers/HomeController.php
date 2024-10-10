<?php

require_once 'models/PostModel.php';
class HomeController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    public function index() {
        $posts = $this->postModel ->getPosts();
        $role = $_SESSION['role'];
        if ($role === 'admin') {
            require_once 'views/admin/dashboard.php';
            return;
        }
        require_once 'views/home.php';
//        require_once 'views/users/login.php';
    }
}
<?php
namespace app\controllers;
use app\core\Controller;
use app\models\PostModel;
use app\utils\View;

//require_once 'models/PostModel.php';

class HomeController extends Controller {
    private PostModel $postModel;

    public function __construct() {
        $this->postModel = new PostModel();
    }

    public function index(): void
    {
        $posts = $this->postModel ->getPosts();
        // Check if the user is logged in and has a role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
//            require_once 'views/admin/dashboard.php';
            View::render('views/admin/dashboard.php', ['posts' => $posts]);
        } else {
//            require_once 'views/home.php';
            View::render('views/home.php', ['posts' => $posts]);
        }
//        require_once 'views/users/login.php';
    }
}
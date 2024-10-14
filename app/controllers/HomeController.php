<?php
namespace app\controllers;
use app\core\Controller;
use app\models\Post;
use app\utils\View;

class HomeController extends Controller {
    private Post $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    /** Base Controller to redirect user based on the role
     * @return void
     */
    public function index(): void
    {
        $posts = $this->postModel ->getPosts();
        // Check if the user is logged in and has a role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            View::render('views/admin/dashboard.php', ['posts' => $posts]);
        } else {
            View::render('views/home.php', ['posts' => $posts]);
        }
    }
}
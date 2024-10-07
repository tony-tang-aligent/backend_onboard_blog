<?php
//require_once 'views/home.php';
require_once 'models/PostModel.php';
class PostsController {
    private $postModel;

    public function __construct() {
        $this->postModel = new PostModel(); // Initialize the PostModel instance
    }
    public function index() {
        $posts = $this->postModel->getPosts();
        require_once 'views/home.php';
    }

    public function create() {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $this->postModel->addPost($title, $body, 1);

//        header('Location: /');
//        exit;  // Ensure the script stops executing after the redirect

        // JavaScript-based redirection after post creation
        echo '<script type="text/javascript">';
        echo 'window.location.href="/";';
        echo '</script>';
        exit; // Ensure no further code runs
    }

    public function show($id) {
        $post = $this->postModel->getPostByID($id);
        $comments = $this->postModel->getCommentByID($id);
        if ($post == null) {
            $post = [];
        }
        if ($comments == null) {
            $comments = [];
        }
//        var_dump($id);
//        var_dump($post);
//        var_dump($comment);
        require_once 'views/posts/show.php';
    }



}
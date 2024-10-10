<?php
//require_once 'views/home.php';
use JetBrains\PhpStorm\NoReturn;

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

    public function create() :void {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $userId = $_SESSION['user_id']; // Make sure the user is logged in
        $role = $_SESSION['role'];
        // Add the post to the database
        if ($role === 'admin') {
            $this->postModel->addPost($title, $body, $userId);
            header("Location: /admin");
        } else {
            $this->postModel->addPost($title, $body, $userId);
            header('Location: /');
            exit; // Ensure no further code runs
        }
    }

    public function show($id) {
        $post = $this->postModel->getPostByID($id);
        $comments = $this->postModel->getCommentByID($id);
        $role = $_SESSION['role'];
        if ($post == null) {
            $post = [];
        }
        if ($comments == null) {
            $comments = [];
        }
//        var_dump($id);
//        var_dump($post);
//        var_dump($comment);
        if ($role === 'admin') {
            require_once 'views/admin/posts/admin_show.php';
        }
        require_once 'views/posts/show.php';
    }

    public function edit($id) {
        $post = $this->postModel->getPostByID($id);
        $role = $_SESSION['role'];
        // Ensure the post exists and that the current user is the owner of the post
        if (!$post ) {
            if ($role === 'admin') {
                header('Location: /admin');
                exit;
            }
            header('Location: /');
            exit;
        }

        require_once 'views/posts/edit.php';
    }

    // Handle the update request after form submission
    public function update($postId) {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $role = $_SESSION['role'];
        // Update the post in the database

        if ($this->postModel->updatePost($postId, $title, $body)) {
            if ($role === 'admin') {
                header('Location: /admin/posts/' . $postId);
                exit;
            }
            header('Location: /posts/' . $postId);
            exit;
        } else {
            echo "Failed to update post.";
        }
    }

    public function delete($id) {
        $post = $this->postModel->getPostByID($id);
        $role = $_SESSION['role'];
        // Ensure the post exists and that the current user is the owner
        if (!$post) {
            header('Location: /');
            exit;
        }

        // Delete the post
        if ($this->postModel->deletePost($id)) {
            if ($role === 'admin') {
                header('Location: /admin');
                exit;
            }
            header('Location: /');
            exit;
        } else {
            echo "Failed to delete post.";
        }
    }


}

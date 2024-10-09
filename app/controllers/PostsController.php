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

        // Add the post to the database
        if ($this->postModel->addPost($title, $body, $userId)) {
            header('Location: /');
            exit; // Ensure no further code runs
        } else {
            echo "Failed to create post.";
        }
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

    public function edit($id) {
        $post = $this->postModel->getPostByID($id);

        // Ensure the post exists and that the current user is the owner of the post
        if (!$post || $post->user_id != $_SESSION['user_id']) {
            header('Location: /');
            exit;
        }

        require_once 'views/posts/edit.php';
    }

    // Handle the update request after form submission
    public function update($id) {
        $title = $_POST['title'];
        $body = $_POST['body'];

        // Update the post in the database
        if ($this->postModel->updatePost($id, $title, $body)) {
            header('Location: /posts/' . $id);
            exit;
        } else {
            echo "Failed to update post.";
        }
    }

    public function delete($id) {
        $post = $this->postModel->getPostByID($id);

        // Ensure the post exists and that the current user is the owner
        if (!$post || $post->user_id != $_SESSION['user_id']) {
            header('Location: /');
            exit;
        }

        // Delete the post
        if ($this->postModel->deletePost($id)) {
            header('Location: /');
            exit;
        } else {
            echo "Failed to delete post.";
        }
    }


}

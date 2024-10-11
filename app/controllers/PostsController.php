<?php
//require_once 'views/home.php';
namespace app\controllers;
use app\models\CommentModel;
use app\models\PostModel;
use JetBrains\PhpStorm\NoReturn;

//require_once 'models/PostModel.php';
//require_once 'models/CommentModel.php';
class PostsController {
    private PostModel $postModel;
    private CommentModel $commentModel;
    public function __construct() {
        $this->postModel = new PostModel(); // Initialize the PostModel instance
        $this->commentModel = new CommentModel();
    }
    public function index(): void
    {
        $posts = $this->postModel->getPosts();
        require_once 'views/home.php';
    }

    public function create() :void {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $userId = $_SESSION['user_id']; // Make sure the user is logged in
        $role = $_SESSION['role'];
        // Add the post to the database
        $this->postModel->addPost($title, $body, $userId);
        if ($role === 'admin') {
            header("Location: /admin");
        } else {
            header('Location: /');
            exit; // Ensure no further code runs
        }
    }

    public function show($id): void
    {
        $post = $this->postModel->getPostByID($id);
        $comments = $this->commentModel->getApprovedComments($id);
        if ($post == null) {
            $post = [];
        }
        if ($comments == null) {
            $comments = [];
        }
//        var_dump($id);
//        var_dump($post);
//        var_dump($comment);
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            require_once 'views/admin/posts/admin_show.php';
        }
        require_once 'views/posts/show.php';
    }

    public function edit($id): void
    {
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
    public function update($postId): void
    {
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

    public function delete($id): void
    {
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

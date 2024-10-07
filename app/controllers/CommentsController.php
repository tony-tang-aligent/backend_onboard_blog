<?php

require_once 'models/CommentModel.php';
require_once 'models/PostModel.php';
class CommentsController {
    private $commentModel;
    private $postModel;

    public function __construct() {
        $this->commentModel = new CommentModel(); // Initialize the PostModel instance
        $this->postModel = new PostModel();
    }

    public function store($postId) {
        $name = $_POST['name'] ?? 'Anonymous';
        $message = $_POST['message'];

        if (strlen($message) > 50) {
            echo "Comment exceeds the maximum character limit.";
            return;
        }

        $this->commentModel->addComment($postId, $name, $message);
        $this->postModel->incrementCommentCount($postId);

        // Redirect to the specific post page
        echo '<script type="text/javascript">';
        echo 'window.location.href="/posts/' . $postId . '";';
        echo '</script>';
        exit; // Ensure no further code runs
    }

}
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
        $name = trim($_POST['name'] ?? '');
        // Check if the name is empty
        if (empty($name)) {
            $name = 'Anonymous'; // Set default name
        }
        $message = $_POST['message'];
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
//        var_dump($name);
        if (strlen($message) > 50) {
            $_SESSION['error'] = "Comment exceeds the maximum character limit.";
            if ($role === 'admin') {
                header('Location: /admin/posts/' . $postId);
                exit;
            }
            header('Location: /posts/' . $postId);
            exit;
        }

        $this->commentModel->addComment($postId, $name, $message, $userId);
        $this->postModel->incrementCommentCount($postId);

        if ($role === 'admin') {
            header('Location: /admin/posts/' . $postId);
            exit;
        }
        header('Location: /posts/' . $postId);
        exit;
//        // Redirect to the specific post page
//        echo '<script type="text/javascript">';
//        echo 'window.location.href="/posts/' . $postId . '";';
//        echo '</script>';
//        exit; // Ensure no further code runs
    }

    public function delete($commentId, $postId) {
        $comment = $this->commentModel->getCommentByID($commentId);
        //var_dump($comment);
        $role = $_SESSION['role'];
        // Ensure the comment exists and belongs to the user (if user-based ownership)
        if (!$comment) {
            header('Location: /posts/' . $postId);
            exit;
        }

        // Delete the comment
        if ($this->commentModel->deleteComment($commentId)) {
            // Decrement comment count in the post
            $this->postModel->decrementCommentCount($postId);

            if ($role === 'admin') {
                header('Location: /admin/posts/' . $postId);
                exit;
            }
            header('Location: /posts/' . $postId);
            exit;
        } else {
            echo "Failed to delete comment.";
        }
    }


    public function edit($commentId, $postId) {
        $comment = $this->commentModel->getCommentByID($commentId);

        $role = $_SESSION['role'];
        // Ensure the post exists and that the current user is the owner of the post
        if (!$comment) {
            if ($role === 'admin') {
                header('Location: /admin/posts/' . $postId);
                exit;
            }
            header('Location: /posts/' . $postId);
            exit;
        }
        require_once 'views/posts/commentedit.php';
    }

    public function update($commentId, $postId) {
        $name = $_POST['name'] ?? 'Anonymous';
        $message = $_POST['message'];
        $role = $_SESSION['role'];
        // Ensure the message does not exceed the character limit
        if (strlen($message) > 50) {
            $_SESSION['error'] = "Comment exceeds the maximum character limit.";
            header("Location: /comments/$commentId/edit/$postId");
            exit;
        }

        // Update the comment in the database
        $this->commentModel->updateComment($commentId, $name, $message);

        // Redirect back to the post page
        //var_dump($postId);
        if ($role === 'admin') {
            header('Location: /admin/posts/' . $postId);
            exit;
        }
        header('Location: /posts/' . $postId);
        exit;
    }



}
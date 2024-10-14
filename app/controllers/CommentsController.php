<?php
namespace app\controllers;

use app\models\Comment;
use app\models\Post;
use app\utils\View;

class CommentsController {
    private Comment $comment;
    private Post $post;

    public function __construct() {
        $this->comment = new Comment(); // Initialize the Post instance
        $this->post = new Post();
    }

    /** API crete and store new comment
     * @param $postId
     * @return void
     */
    public function store($postId) {
        $name = trim($_POST['name'] ?? 'Anonymous');
        $message = $_POST['message'] ?? '';
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        if (strlen($message) > 50) {
            $_SESSION['error'] = "Comment exceeds the maximum character limit.";
            header("Location: $redirectUrl");
            exit;
        }

        $this->comment->add($postId, $name, $message, $userId);
        $this->post->incrementCommentCount($postId);

        // Redirect after adding the comment
        header("Location: $redirectUrl");
        exit;
    }

    /** API delete a post
     * @param $commentId
     * @param $postId
     * @return void
     */
    public function delete($commentId, $postId): void
    {
        $comment = $this->comment->getCommentByID($commentId);
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        // Ensure the comment exists
        if (!$comment) {
            header("Location: $redirectUrl");
            exit;
        }

        // Delete the comment and decrement count
        if ($this->comment->delete($commentId)) {
            $this->post->decrementCommentCount($postId);
        }

        // Redirect after the action
        header("Location: $redirectUrl");
        exit;
    }


    /** Edit a comment
     * @param $commentId
     * @param $postId
     * @return void
     */
    public function edit($commentId, $postId): void
    {
        $comment = $this->comment->getCommentByID($commentId);
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        // Ensure the comment exists
        if (!$comment) {
            header("Location: $redirectUrl");
            exit;
        }

        // Render the appropriate view based on the role
        $view = ($role === 'admin') ? 'views/admin/admin_comment_edit.php' : 'views/posts/commentedit.php';
        View::render($view, ['postId' => $postId, 'comment' => $comment]);
    }

    /** Update a comment
     * @param $commentId
     * @param $postId
     * @return void
     */
    public function update($commentId, $postId) {
        $name = $_POST['name'] ?? 'Anonymous';
        $message = $_POST['message'] ?? '';
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        // Ensure the message does not exceed the character limit
        if (strlen($message) > 50) {
            $_SESSION['error'] = "Comment exceeds the maximum character limit.";
            header("Location: /comments/$commentId/edit/$postId");
            exit;
        }

        // Update the comment in the database
        $this->comment->updateComment($commentId, $name, $message);

        // Redirect after updating the comment
        header("Location: $redirectUrl");
        exit;
    }
}
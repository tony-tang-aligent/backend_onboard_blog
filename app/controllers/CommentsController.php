<?php
namespace app\controllers;

use app\models\Comment;
use app\models\Post;
use app\utils\View;

class CommentsController {

    public function __construct(
        private Comment $comment,
        private Post $post
    ) {}

    /** API crete and store new comment
     * @param $postId
     * @return void
     */
    public function add($postId) {
        $name = trim($_POST['name'] ?? 'Anonymous');
        $message = $_POST['message'] ?? '';
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        // Initialize error message
        $error = null;

        // Validate the message length
        if (strlen($message) > 50) {
            $error = "Comment exceeds the maximum character limit.";
        } else {
            $postData = [
                'postId' => $postId,
                'name' => $name,
                'message' => $message,
                'userId' => $userId
            ];
            $this->comment->create($postData);
        }

        // Set error message in session if exists
        if ($error) {
            $_SESSION['error'] = $error;
        }
        // Redirect after adding the comment or encountering an error
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
        $comment = $this->comment->getByID($commentId);
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        $error = null;
        // Ensure the comment exists
        if (!$comment) {
            $error = "Comment not found.";
        } else {
            // Delete the comment and decrement count
            $this->comment->delete($commentId);
        }
        // Set error message in session if exists
        if ($error) {
            $_SESSION['error'] = $error;
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
        $comment = $this->comment->getByID($commentId);
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
     * @param array $data
     * @return void
     */
    public function update($commentId, $postId, array $data) {
        $name = $_POST['name'] ?? 'Anonymous';
        $message = $_POST['message'] ?? '';
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";

        $error = null;
        if (strlen($message) > 50) {
            $error = "Comment exceeds the maximum character limit.";
        } else {
            // Prepare post data
            $postData = [
                'commentId' => $commentId,
                'name' => $name,
                'message' => $message,
            ];

            // Attempt to update the comment in the database
            try {
                $this->comment->update($commentId, $postData);
            } catch (\Exception $e) {
                $error = "Failed to update comment: " . $e->getMessage();
            }
        }
        // Set error message in session if exists
        if ($error) {
            $_SESSION['error'] = $error;
            // Redirect to edit page on error
            $redirectUrl = "/comments/$commentId/edit/$postId";
        }

        // Redirect after the action
        header("Location: $redirectUrl");
        exit;
    }
}
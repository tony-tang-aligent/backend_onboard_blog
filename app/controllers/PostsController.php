<?php
namespace app\controllers;
use app\models\Comment;
use app\models\Post;
use app\utils\View;
class PostsController {
    private Post $postModel;
    private Comment $commentModel;
    public function __construct() {
        $this->postModel = new Post(); // Initialize the Post instance
        $this->commentModel = new Comment();
    }

    /** API show all the posts
     * @return void
     */
    public function index(): void
    {
        $posts = $this->postModel->getPosts();
        View::render('views/home.php',['posts'=>$posts]);
    }

    /** API create a new post
     * @return void
     */
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

    /** API show a specific post
     * @param $id
     * @return void
     */
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
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            View::render('views/admin/posts/admin_show.php',['post'=>$post, 'comments'=>$comments]);
        }
        View::render('views/posts/show.php',['post'=>$post, 'comments'=>$comments]);
    }

    /** API edit a post
     * @param $id
     * @return void
     */
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
        View::render('views/posts/edit.php',['post'=>$post]);
    }

    /** API Handle the update request after form submission
     * @param $postId
     * @return void
     */
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

    /** API delete a post
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $post = $this->postModel->getPostByID($id);
        $role = $_SESSION['role'];
        // Ensure the post exists
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

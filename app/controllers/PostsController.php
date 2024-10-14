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
    public function create(): void {
        $title = $_POST['title'] ?? '';
        $body = $_POST['body'] ?? '';
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        // Add the post to the database
        $this->postModel->addPost($title, $body, $userId);

        // Set the redirect URL based on the role
        $redirectUrl = ($role === 'admin') ? '/admin' : '/';
        header("Location: $redirectUrl");
        exit;
    }

    /** API show a specific post
     * @param $id
     * @return void
     */
    public function show($id): void
    {
        $post = [];
        $comments = [];
        $post = $this->postModel->getPostByID($id);
        $comments = $this->commentModel->getApprovedComments($id);
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            View::render('views/admin/posts/admin_show.php',['post'=>$post, 'comments'=>$comments]);
        }
        View::render('views/posts/show.php',['post'=>$post, 'comments'=>$comments]);
    }

    /** API edit a post
     * @param $id
     * @return void
     */
    public function edit($id): void {
        $post = $this->postModel->getPostByID($id);
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? '/admin' : '/';

        // Ensure the post exists
        if (!$post) {
            header("Location: $redirectUrl");
            exit;
        }

        // Render the view
        View::render('views/posts/edit.php', ['post' => $post]);
    }

    /** API Handle the update request after form submission
     * @param $postId
     * @return void
     */
    public function update($postId): void {
        $title = $_POST['title'] ?? '';
        $body = $_POST['body'] ?? '';
        $role = $_SESSION['role'];

        // Update the post in the database
        if ($this->postModel->updatePost($postId, $title, $body)) {
            // Redirect based on the role
            $redirectUrl = ($role === 'admin') ? "/admin/posts/$postId" : "/posts/$postId";
            header("Location: $redirectUrl");
            exit;
        }
    }

    /** API delete a post
     * @param $id
     * @return void
     */
    public function delete($id): void {
        $post = $this->postModel->getPostByID($id);
        $role = $_SESSION['role'];
        $redirectUrl = ($role === 'admin') ? '/admin' : '/';

        // Ensure the post exists
        if (!$post) {
            header("Location: $redirectUrl");
            exit;
        }

        // Delete the post and redirect
        if ($this->postModel->deletePost($id)) {
            header("Location: $redirectUrl");
            exit;
        }
    }
}

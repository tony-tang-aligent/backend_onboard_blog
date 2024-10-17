<?php
namespace app\controllers;
use app\core\Controller;
use app\models\Comment;
use app\models\Post;
use app\utils\View;

class HomeController extends Controller {
    public function __construct(
        private Post $post,
        private Comment $comment
    ) {}

    /** Base Controller to redirect user based on the role
     * @return void
     */
    public function index(): void
    {
        $posts = $this->post ->getAll();
        $commentCounts = [];

        // Loop through posts to get comment counts
        foreach ($posts as $post) {
            $commentCounts[$post->id] = $this->comment->getCommentCount($post->id);
        }

        xdebug_info();
        // Check if the user is logged in and has a role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            View::render('views/admin/dashboard.php', ['posts' => $posts, 'commentCounts' => $commentCounts]);
        } else {
            View::render('views/home.php', ['posts' => $posts, 'commentCounts' => $commentCounts]);
        }
    }
}
<?php
//require_once 'models/PostModel.php';
//require_once 'models/CommentModel.php';
//require_once 'models/UserModel.php';
namespace app\controllers;
use app\models\CommentModel;
use app\models\PostModel;
use app\models\UserModel;
use app\utils\View;

class AdminController {

    private PostModel $postModel;
    private CommentModel $commentModel;
    private UserModel $userModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $posts = $this->postModel ->getPosts();
//        require_once 'views/admin/dashboard.php';
        View::render('views/admin/dashboard.php', ['posts' => $posts]);
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
//        require_once 'views/admin/admin_show.php';
        View::render('views/admin/admin_show.php', ['post' => $post, 'comments' => $comments]);
    }

    public function users(): void
    {
        $users = $this->userModel->findAllUsers();
//        require_once 'views/admin/usermanagement.php';
        View::render('views/admin/usermanagement.php', ['users' => $users]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->findUserByUsername($username)) {
                echo "Username is already taken.";
                return;
            }

            if ($this->userModel->findUserByEmail($email)) {
                echo "Email is already registered";
                return;
            }

            $this->userModel->register($username, $email, $password);
            header("Location: /admin/users");
        } else {
            $this->tosignup();
        }
    }

    public function edit($id): void
    {
        $user = $this->userModel->findUserByID($id);
//        require_once 'views/admin/admin_user_edit.php';
        View::render('views/admin/admin_user_edit.php', ['user' => $user]);
    }

    public function update($id): void
    {
        $user = $this->userModel->findUserByID($id);
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password']; // This might be blank

        // If the password field is not empty, hash the new password
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // Use the existing hashed password if no new password is provided
            $hashedPassword = $user->password;
        }

        // Update the user's data
        $this->userModel->updateUser($id, $username, $email, $hashedPassword, $role);

        // Redirect back to the user management page
        header("Location: /admin/users");
    }

    public function delete($id): void
    {
        $this->userModel->deleteUserByID($id);
        header("Location: /admin/users");
    }

    public function showComment(): void
    {
        $comments = $this->commentModel->getAllComments();
//        require_once 'views/admin/approve_comments.php';
        View::render('views/admin/approve_comments.php',['comments'=>$comments]);
    }

//    public function showSingleComment($id): void
//    {
//        $comment
//    }

    public function approve($commentId): void
    {
        $this->commentModel->approveCommentStatus($commentId);
        header("Location: /admin/comments");
    }

    public function reject($commentId): void
    {
        $this->commentModel->rejectCommentStatus($commentId);
        header("Location: /admin/comments");
    }
}
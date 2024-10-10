<?php
require_once 'models/PostModel.php';
require_once 'models/CommentModel.php';
require_once 'models/UserModel.php';
class AdminController {

    private $postModel;
    private $commentModel;
    private $userModel;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        $posts = $this->postModel ->getPosts();
        require_once 'views/admin/dashboard.php';
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
        require_once 'views/admin/admin_show.php';
    }

    public function users() {
        $users = $this->userModel->findAllUsers();
        require_once 'views/admin/usermanagement.php';
    }

    public function create() {
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

    public function edit($id) {
        $user = $this->userModel->findUserByID($id);
        require_once 'views/admin/admin_user_edit.php';
    }

    public function update($id)
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

    public function delete($id)
    {
        $this->userModel->deleteUserByID($id);
        header("Location: /admin/users");
    }
}
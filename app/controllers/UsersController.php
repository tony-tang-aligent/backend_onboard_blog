<?php

class UsersController {

    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function signup() {
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
            echo "Registration successfull";
        } else {
            require_once 'views/signup.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Find the user by username
            $user = $this->userModel->findUserByUsername($username);

            if ($user && password_verify($password, $user->password)) {
                // Start a session and store user information
                session_start();
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role;

                // Redirect to the home page or posts page
                echo '<script type="text/javascript">';
                echo 'window.location.href="/posts";';
                echo '</script>';
                exit;
            } else {
                echo "Invalid username or password.";
            }
        } else {
            // Show login form
            require_once 'views/login.php';
        }
    }
}
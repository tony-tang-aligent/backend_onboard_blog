<?php

require_once 'models/UserModel.php';
class UsersController {

    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

//    public function tosignup() {
//        if (headers_sent()) {
//            // Output has been sent, cannot use header()
//            echo '<script type="text/javascript">window.location.href="/signup";</script>';
//            exit;
//        } else {
//            header('Location: /signup');
//            exit;
//        }
//    }
//
//    public function tologin() {
//        // Redirect to login page
//        if (headers_sent()) {
//            // Output has been sent, cannot use header()
//            echo '<script type="text/javascript">window.location.href="/login";</script>';
//            exit;
//        } else {
//            header('Location: /login');
//            exit;
//        }
//    }

    public function tosignup() {
        require_once 'views/users/signup.php';
    }

    public function tologin() {
        require_once 'views/users/login.php';
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
            require_once 'views/users/signup.php';
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
            require_once 'views/users/login.php';
        }
    }
}
<?php
namespace app\controllers;
//require_once 'models/UserModel.php';
use app\models\UserModel;
use app\utils\View;

class UsersController {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function tosignup(): void
    {
//        require_once 'views/users/signup.php';
        View::render('views/users/signup.php');
    }

    public function tologin(): void
    {
//        require_once 'views/users/login.php';
        View::render('views/users/login.php');
    }

    public function signup(): void
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
            header("Location: /showlogin");
        } else {
            $this->tosignup();
        }
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Find the user by username
            $user = $this->userModel->findUserByUsername($username);

            if ($user && password_verify($password, $user->password)) {
                // Start a session and store user information
//                session_start();
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role;
                if ($user->role === 'admin') {
                    header("Location: /admin");
                    exit;
                }
                header("Location: /");
                exit;
            } else {
                //var_dump($user);
                echo "Invalid username or password.";
            }
        } else {
            // Show login form
            $this->tologin();
        }
    }

    public function logout() : void {
        session_unset();
        session_destroy();

        header("Location: /showlogin");
        exit();
    }
}
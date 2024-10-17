<?php
namespace app\controllers;
use app\models\User;
use app\utils\View;

class UsersController {

    public function __construct(
        private User $user
    ) {}

    /** direct to the signup page
     * @return void
     */
    public function tosignup(): void
    {
        View::render('views/users/signup.php');
    }

    /** direct to the login page
     * @return void
     */
    public function tologin(): void
    {
        View::render('views/users/login.php');
    }

    /** API handle the signup request
     * @return void
     */
    public function signup(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->getUserByUsername($username)) {
                $_SESSION['flash_message'] = "Username is already taken.";
                return;
            }

            if ($this->user->getUserByEmail($email)) {
                $_SESSION['flash_message'] = "Email is already registered.";
                return;
            }

            $this->user->create($username, $email, $password);
            header("Location: /showlogin");
        } else {
            $this->tosignup();
        }
    }

    /** Handle the login logic
     * @return void
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Find the user by username
            $user = $this->user->getUserByUsername($username);

            if ($user && password_verify($password, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role;
                $redirectUrl = ($user->role === 'admin') ? '/admin' : '/';
                header("Location: $redirectUrl");
                exit;
            } else {
                $_SESSION['flash_message'] = "Invalid username or password.";
            }
        } else {
            // Show login form
            $this->tologin();
        }
    }

    /** handle the logout, delete session
     * @return void
     */
    public function logout() : void {
        session_unset();
        session_destroy();

        header("Location: /showlogin");
        exit();
    }
}
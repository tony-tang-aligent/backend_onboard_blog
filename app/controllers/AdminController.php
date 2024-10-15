<?php
namespace app\controllers;
use app\models\Comment;
use app\models\Post;
use app\models\User;
use app\utils\View;
use Exception;

class AdminController {

    private Post $postModel;
    private Comment $commentModel;
    private User $userModel;

    public function __construct() {
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->userModel = new User();
    }

    /** display the admin dashboard
     * @return void
     */
    public function index(): void
    {
        $posts = $this->postModel ->getPosts();
        View::render('views/admin/dashboard.php', ['posts' => $posts]);
    }

    /** display a post for the admin user
     * @param $id
     * @return void
     */
    public function show($id): void
    {
        $post = [];
        $comments = [];
        $post = $this->postModel->getPostByID($id);
        $comments = $this->commentModel->getApprovedComments($id);
        View::render('views/admin/admin_show.php', ['post' => $post, 'comments' => $comments]);
    }

    /** display all the users for the admin user
     * @return void
     */
    public function users(): void
    {
        $users = $this->userModel->findAllUsers();
        View::render('views/admin/usermanagement.php', ['users' => $users]);
    }

    /** API for creating a new user for admin
     * @return void
     */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->findUserByUsername($username)) {
                $_SESSION['flash_message'] = "Username is already taken.";
                return;
            }

            if ($this->userModel->findUserByEmail($email)) {
                $_SESSION['flash_message'] = "Email is already registered.";
                return;
            }

            $this->userModel->create($username, $email, $password);
            $_SESSION['flash_message'] = "User created successfully.";
            header("Location: /admin/users");
        } else {
            http_response_code(400);
            $this->handleError("Invalid request method. User creation requires a POST request.");
        }
    }

    /** API edit user
     * @param $id
     * @return void
     */
    public function edit($id): void
    {
        $user = $this->userModel->findUserByID($id);
        View::render('views/admin/admin_user_edit.php', ['user' => $user]);
    }

    /** Update the information of a user
     * @param $id
     * @return void
     */
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
        $this->userModel->update($id, $username, $email, $hashedPassword, $role);
        // Redirect back to the user management page
        header("Location: /admin/users");
    }

    /** API delete a user
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $this->userModel->delete($id);
        header("Location: /admin/users");
    }

    /** API show a specific comment
     * @return void
     */
    public function showComment(): void
    {
        $comments = $this->commentModel->getAllPendingComments();
        View::render('views/admin/approve_comments.php',['comments'=>$comments]);
    }

    /** update the status of a comment based on the parameter
     * @param $commentId
     * @param $status
     * @return void
     */
    public function changeCommentStatus($commentId, $status): void
    {
        if ($status === 'approved' || $status === 'rejected') {
            $this->commentModel->updateCommentStatus($commentId, $status);
        }
        header("Location: /admin/comments");
    }

    /** Output an error message
     * @param string $errorMessage
     * @return void
     */
    private function handleError(string $errorMessage): void
    {
        echo "<div style='color: red; font-weight: bold;'>Error: $errorMessage</div>";
    }
}
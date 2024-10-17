<?php
use app\controllers\AdminController;
use app\controllers\CommentsController;
use app\controllers\HomeController;
use app\controllers\PostsController;
use app\controllers\UsersController;
use app\core\Router;
use app\middleware\AuthMiddleware;
use app\core\Database;
use app\models\Post;
use app\models\User;
use app\models\Comment;
session_start();
function my_custom_autoloader($class_name): void
{
    // Replace namespace separator with directory separator
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    // Remove the "app" prefix from the class name
    // This assumes your namespace is always "app"
    if (str_starts_with($class_name, 'app' . DIRECTORY_SEPARATOR)) {
        $class_name = substr($class_name, strlen('app' . DIRECTORY_SEPARATOR));
    }
    // Define the base directory for the application
    $base_dir = __DIR__ . '/'; // Points to /var/www/html/
    // Construct the file path
    $file = $base_dir . $class_name . '.php';
    // Require the file if it exists
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Log or display an error if the class file is not found
        echo "Class file for {$class_name} not found at {$file}\n";
    }
}

// Register the autoloader
spl_autoload_register('my_custom_autoloader');

$router = new Router();
$db = new Database();
$user = new User($db);
$post = new Post($db);
$comment = new Comment($db);

// Define all routes before the session check
// Routes for user signup , login and logout
$router->add('GET', '/showsignup', [new UsersController($user), 'tosignup']);
$router->add('GET', '/showlogin', [new UsersController($user), 'tologin']);
$router->add('POST', '/signup', [new UsersController($user), 'signup']);
$router->add('POST', '/login', [new UsersController($user), 'login']);
$router->add('GET', '/logout', [new UsersController($user), 'logout']);


// Functional routes that require session
$router->add('GET', '/', [new HomeController($post, $comment), 'index']);
$router->add('GET', '/posts', [new PostsController($post, $comment), 'index']);
$router->add('GET', '/posts/{id}', [new PostsController($post, $comment), 'show']);
$router->add('GET', '/posts/{id}/edit', [new PostsController($post, $comment), 'edit']);
$router->add('POST', '/posts/{id}/update', [new PostsController($post, $comment), 'update']);
$router->add('POST', '/posts/{id}/delete', [new PostsController($post, $comment), 'delete']);
$router->add('POST', '/posts/{id}/comments', [new CommentsController($comment), 'add']);
$router->add('POST', '/comments/{commentId}/posts/{postId}/update', [new CommentsController($comment), 'update']);
$router->add('GET', '/comments/{id}', [new CommentsController($comment), 'show']);
$router->add('POST', '/comments/{commentId}/delete/{postId}', [new CommentsController($comment), 'delete']);
$router->add('GET', '/comments/{commentId}/edit/{postId}', [new CommentsController($comment), 'edit']);

// Admin routing
$router->add('GET', '/admin', function () use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->index());
});

$router->add('GET', '/admin/posts/{id}', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->show($id));
});

$router->add('GET', '/admin/users', function () use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->users());
});

$router->add('POST', '/admin/users/create', function () use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->create());
});

$router->add('GET', '/admin/users/{id}/edit', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->edit($id));
});

$router->add('POST', '/admin/users/{id}/update', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->update($id));
});

$router->add('POST', '/admin/users/{id}/delete', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->delete($id));
});

$router->add('GET', '/admin/comments', function () use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->showComment());
});

$router->add('GET', '/admin/comments/{id}/approve', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->changeCommentStatus($id, 'approve'));
});

$router->add('GET', '/admin/comments/{id}/reject', function ($id) use ($post, $comment, $user) {
    return AuthMiddleware::checkAdminPermissions(fn() => (new AdminController($post, $comment, $user))->changeCommentStatus($id, 'reject'));
});


// Only allow post creation if user is logged in
$router->add('POST', '/posts/create', function() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /showlogin');
        exit;
    }
    // If logged in, call the create function of PostsController
    (new PostsController())->create();
});

// Get the request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Resolve the route
$router->resolve($requestMethod, $requestUri);

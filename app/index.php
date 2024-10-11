<?php
//namespace app;
session_start();

//require_once 'core/App.php';
//require_once 'core/Controller.php';
//require_once 'controllers/HomeController.php';
//require_once 'core/Router.php';
//require_once 'controllers/PostsController.php';
//require_once 'controllers/CommentsController.php';
//require_once 'controllers/UsersController.php';
//require_once 'controllers/AdminController.php';

function my_custom_autoloader($class_name) {
    // Replace namespace separator with directory separator
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);

    // Remove the "app" prefix from the class name
    // This assumes your namespace is always "app"
    if (strpos($class_name, 'app' . DIRECTORY_SEPARATOR) === 0) {
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



use app\controllers\AdminController;
use app\controllers\CommentsController;
use app\controllers\HomeController;
use app\controllers\PostsController;
use app\controllers\UsersController;
use app\core\Router;

$router = new Router();

// Define all routes before the session check
// Routes for user signup , login and logout
$router->add('GET', '/showsignup', [new UsersController(), 'tosignup']);
$router->add('GET', '/showlogin', [new UsersController(), 'tologin']);
$router->add('POST', '/signup', [new UsersController(), 'signup']);
$router->add('POST', '/login', [new UsersController(), 'login']);
$router->add('GET', '/logout', [new UsersController(), 'logout']);


// Functional routes that require session
$router->add('GET', '/', [new HomeController(), 'index']);
$router->add('GET', '/posts', [new PostsController(), 'index']);
$router->add('GET', '/posts/{id}', [new PostsController(), 'show']);
$router->add('GET', '/posts/{id}/edit', [new PostsController(), 'edit']);
$router->add('POST', '/posts/{id}/update', [new PostsController(), 'update']);
$router->add('POST', '/posts/{id}/delete', [new PostsController(), 'delete']);
$router->add('POST', '/posts/{id}/comments', [new CommentsController(), 'store']);
$router->add('POST', '/comments/{commentId}/posts/{postId}/update', [new CommentsController(), 'update']);
$router->add('GET', '/comments/{id}', [new CommentsController(), 'show']);
$router->add('POST', '/comments/{commentId}/delete/{postId}', [new CommentsController(), 'delete']);
$router->add('GET', '/comments/{commentId}/edit/{postId}', [new CommentsController(), 'edit']);

//Admin routing
$router->add('GET', '/admin', [new AdminController(), 'index']);
$router->add('GET', '/admin/posts/{id}', [new AdminController(), 'show']);
$router->add('GET', '/admin/users', [new AdminController(), 'users']);
$router->add('POST','/admin/users/create', [new AdminController(), 'create']);
$router->add('GET', '/admin/users/{id}/edit', [new AdminController(), 'edit']);
$router->add('POST', '/admin/users/{id}/update', [new AdminController(), 'update']);
$router->add('POST', '/admin/users/{id}/delete', [new AdminController(), 'delete']);
$router->add('GET', '/admin/comments', [new AdminController(), 'showComment']);
$router->add('GET', '/admin/comments/{id}/approve', [new AdminController(), 'approve']);
$router->add('GET', '/admin/comments/{id}/reject', [new AdminController(), 'reject']);

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

<?php
session_start();
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'controllers/HomeController.php';
require_once 'core/Router.php';
require_once 'controllers/PostsController.php';
require_once 'controllers/CommentsController.php';
require_once 'controllers/UsersController.php';
require_once 'controllers/AdminController.php';

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

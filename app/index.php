<?php
session_start();
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'controllers/HomeController.php';
require_once 'core/Router.php';
require_once 'controllers/PostsController.php';
require_once 'controllers/CommentsController.php';
require_once 'controllers/UsersController.php';

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
$router->add('POST', '/posts/{id}/comments', [new CommentsController(), 'store']);
$router->add('GET', '/comments/{id}', [new CommentsController(), 'show']);

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

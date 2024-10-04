<?php

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'controllers/HomeController.php';
require_once 'core/Router.php';
require_once 'controllers/PostsController.php';
require_once 'controllers/CommentsController.php';
$app = new App();

$router = new Router();

$router ->add('GET', '/', [new HomeController(), 'index']);
$router->add('POST', '/posts/create', [new PostsController(), 'create']);
$router->add('GET', '/posts/{id}', [new PostsController(), 'show']);
$router->add('POST', '/posts/{id}/comments', [new CommentsController(), 'store']);
$router->add('GET', '/comments/{id}',[new CommentsController(), 'show']);
// Get the request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Resolve the route
$router->resolve($requestMethod, $requestUri);
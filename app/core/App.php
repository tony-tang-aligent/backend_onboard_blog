<?php
namespace app\core;
class App {
    public function __construct() {
        $controllerName = 'HomeController'; // Default controller
        $method = 'index'; // Default method
        // Include the controller
        require_once('controllers/' . $controllerName . '.php');
        // Create the controller instance
        $controller = new $controllerName();
        // Call the method
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            echo "Method not found.";
        }
    }
}

<?php

// app/core/App.php
class App {
    public function __construct() {
        $controllerName = 'HomeController'; // Default controller
        $method = 'index'; // Default method

        // You can add logic to handle dynamic routing here

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

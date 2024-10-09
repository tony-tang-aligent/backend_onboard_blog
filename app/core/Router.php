<?php

class Router {
    private $routes = [];

    public function add($method, $path, $action) {
        // Convert path with parameters into a regular expression
        $pattern = preg_replace('/{(\w+)}/', '(\d+)', $path);
        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'action' => $action,
        ];
    }

    public function resolve($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove the full match
                return call_user_func_array($route['action'], $matches);
            }
        }

        // Handle 404 - Not Found
        http_response_code(404);
        //echo "404 Not Found";
        exit;
    }
}

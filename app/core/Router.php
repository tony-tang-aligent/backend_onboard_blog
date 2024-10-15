<?php
namespace app\core;

class Router {
    private array $routes = [];

    /** Adding route to the routes
     * @param $method
     * @param $path
     * @param $action
     * @return void
     */
    public function add($method, $path, $action): void
    {
        // Convert path with parameters into a regular expression
        $pattern = preg_replace('/{(\w+)}/', '(\d+)', $path);
        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'action' => $action,
        ];
    }

    /** resolve a route
     * @param $method
     * @param $uri
     * @return mixed|void
     */
    public function resolve($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove the full match
                return call_user_func_array($route['action'], $matches);
            }
        }

        // Handle 404 - Not Found
        http_response_code(404);
        exit;
    }
}
?>

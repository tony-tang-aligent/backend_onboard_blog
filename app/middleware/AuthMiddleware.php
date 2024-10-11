<?php
namespace app\middleware;

class AuthMiddleware {
    public static function checkAdminPermissions() {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            // Redirect to a 404 page or an error page
            header("HTTP/1.0 404 Not Found");
            include __DIR__ . '/../views/404.php'; // Make sure you have a 404.php file in the views directory
            exit;
        }
    }
}

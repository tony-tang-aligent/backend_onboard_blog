<?php

namespace app\utils;

class View {
    public static function render($view, $data = []): void
    {
        // Extract data to variables
        extract($data);

        // Construct the path to the view file
        $viewFile = $view;
//        $viewFile = $view;
        // Check if the view file exists
        //echo "Rendering view file: {$viewFile}";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Log or display an error if the view file is not found
            echo "View file for {$view} not found at {$viewFile}\n";
            http_response_code(404);
            include __DIR__ . '/../views/404.php'; // Include a 404 page
            exit;
        }
    }
}
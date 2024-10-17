<?php

namespace app\core;
/**
 * Base Controller
 */
class Controller {
    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }
}

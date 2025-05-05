<?php
// Auto-load core classes
define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function ($class) {
    foreach (['core', 'app/Controllers', 'app/Models'] as $folder) {
        $file = BASE_PATH . '/' . $folder . '/' . $class . '.php';
        //echo "Looking for $file<br>";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Start session
session_start();

// Run router
$router = new Router();
$router->dispatch();

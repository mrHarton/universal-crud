<?php
// Auto-load core classes
spl_autoload_register(function ($class) {
    foreach (['core', 'app/Controllers', 'app/Models'] as $folder) {
        $file = __DIR__ . '/../' . $folder . '/' . $class . '.php';
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

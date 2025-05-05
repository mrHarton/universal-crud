<?php

class Router
{
    public function dispatch()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = array_values(array_filter(explode('/', $uri)));  // ← Убираем пустые сегменты
        
        $controllerName = $segments[0] ?? 'home';
        $actionName = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClass . '.php';

        // Отладка
        // echo "<pre>";
        // echo "URI: $uri\n";
        // echo "Controller: $controllerClass\n";
        // echo "Action: $actionName\n";
        // echo "Looking for file: $controllerFile\n";
        // echo "</pre>";

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "Controller file not found";
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller class $controllerClass not found";
            return;
        }

        $controller = new $controllerClass;

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            echo "Method $actionName not found in $controllerClass";
            return;
        }

        // Всё ок — вызываем метод
        call_user_func_array([$controller, $actionName], $params);
    }
}

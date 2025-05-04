<?php
class Router
{
    public function dispatch()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);

        $controllerName = $segments[0] ?? 'home';
        $actionName = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClass . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerClass;
            if (method_exists($controller, $actionName)) {
                call_user_func_array([$controller, $actionName], $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}

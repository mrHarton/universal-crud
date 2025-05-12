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

        if ($controllerName === 'collections') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();

            // Спец-маршрут: просмотр коллекции
            if (($segments[2] ?? '') === 'view') {
                $controller->view($segments[1]);
                return;
            }

            // Спец-маршрут: добавление записи
            if (($segments[2] ?? '') === 'add') {
                $controller->add($segments[1]);
                return;
            }

            // Форма редактирования
            if (($segments[2] ?? '') === 'edit') {
                $controller->edit($segments[1], $segments[3] ?? null);
                return;
            }

            // Обновление записи
            if (($segments[2] ?? '') === 'update') {
                $controller->update($segments[1], $segments[3] ?? null);
                return;
            }

            // Удаление записи
            if (($segments[2] ?? '') === 'delete') {
                $controller->delete($segments[1], $segments[3] ?? null);
                return;
            }

            if (isset($segments[1])) {
                return $controller->view($segments[1]);
            } else {
                return $controller->collections();
            }
    
        }

        if ($controllerName === "admin") {
            require_once __DIR__ . '/../app/Controllers/AdminController.php';
            $controller = new AdminController();

            if (($segments[3] ?? '') === 'edit') {
                return $controller->edit($segments[2]);
            } 
        }

        if ($uri === 'login') {
            require_once __DIR__ . '/../app/Controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
            return;
        }

        if ($uri === 'logout') {
            require_once __DIR__ . '/../app/Controllers/AuthController.php';
            $controller = new AuthController();
            $controller->logout();
            return;
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClass . '.php';

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
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

        // Спец-маршрут: просмотр коллекции
        if ($controllerName === 'collections' && ($segments[2] ?? '') === 'view') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();
            $controller->view($segments[1]);
            return;
        }

        // Спец-маршрут: добавление записи
        if ($controllerName === 'collections' && ($segments[2] ?? '') === 'add') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();
            $controller->add($segments[1]);
            return;
        }

        // Форма редактирования
        if ($controllerName === 'collections' && ($segments[2] ?? '') === 'edit') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();
            $controller->edit($segments[1], $segments[3] ?? null);
            return;
        }

        // Обновление записи
        if ($controllerName === 'collections' && ($segments[2] ?? '') === 'update') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();
            $controller->update($segments[1], $segments[3] ?? null);
            return;
        }

        // Удаление записи
        if ($controllerName === 'collections' && ($segments[2] ?? '') === 'delete') {
            require_once __DIR__ . '/../app/Controllers/CollectionController.php';
            $controller = new CollectionController();
            $controller->delete($segments[1], $segments[3] ?? null);
            return;
        }

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

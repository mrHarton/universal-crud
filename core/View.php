<?php

class View
{
    public static function render(string $view, array $params = []): void
    {
        extract($params);

        $viewFile = __DIR__ . '/../app/Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View '$view' not found: $viewFile");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require __DIR__ . '/../app/Views/layouts/main.php';
    }
}

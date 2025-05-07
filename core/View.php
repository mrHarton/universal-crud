<?php

class View
{
    protected static array $sections = [];
    protected static ?string $currentSection = null;

    public static function start(string $name): void
    {
        self::$currentSection = $name;
        ob_start();
    }

    public static function end(): void
    {
        if (!self::$currentSection) {
            throw new Exception("Нет активной секции для завершения");
        }

        self::$sections[self::$currentSection] = trim(ob_get_clean());
        self::$currentSection = null;
    }

    public static function section(string $name): ?string
    {
        return self::$sections[$name] ?? null;
    }

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

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Universal CRUD' ?></title>
</head>
<body>

    <header>
        <?php if (Auth::check()): ?>
            <p>Привет, <?= Auth::user()['username'] ?> (<?= Auth::user()['role'] ?>) | <a href="/logout">Выйти</a></p>
        <?php else: ?>
            <a href="/login">Войти</a>
        <?php endif; ?>
        <hr>
    </header>

    <main>
        <?= $content ?>
    </main>

</body>
</html>

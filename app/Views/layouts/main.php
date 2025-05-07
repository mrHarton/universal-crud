<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= View::section('title') ?? 'Universal CRUD' ?></title>
    <style>
        body {
            font-family: sans-serif;
            margin: 2em;
        }

        nav a {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?php if (Auth::check()): ?>
        <p>Привет, <?= Auth::user()['username'] ?> (<?= Auth::user()['role'] ?>) | <a href="/logout">Выйти</a></p>
    <?php else: ?>
        <a href="/login">Войти</a>
    <?php endif; ?>

    <nav>
        <a href="/">Главная</a>
        <a href="/admin/dashboard">Админка</a>
        <a href="/admin/collections">Коллекции</a>
    </nav>

    <hr>

    <?= $content ?>

    <hr>
    <footer>
        <small>&copy; <?= date('Y') ?> Universal CRUD</small>
    </footer>
</body>

</html>
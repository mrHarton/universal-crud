<h1>Все коллекции</h1>

<?php if (empty($collections)): ?>
    <p>Пока нет созданных коллекций.</p>
<?php else: ?>
    <ul>
        <?php foreach ($collections as $collection): ?>
            <li><?= htmlspecialchars($collection['name']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="/admin/create">Создать новую коллекцию</a></p>

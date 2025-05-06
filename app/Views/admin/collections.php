<h1>Список коллекций</h1>

<a href="/admin/create">➕ Создать новую коллекцию</a>

<ul>
    <?php foreach ($collections as $collection): ?>
        <li>
            <strong><?= htmlspecialchars($collection['name']) ?></strong> —
            <code>collection_<?= htmlspecialchars($collection['table_name']) ?></code>
            &nbsp;
            <a href="/collections/<?= urlencode($collection['table_name']) ?>/view">Открыть</a>
        </li>
    <?php endforeach; ?>
</ul>

<h1>Список коллекций</h1>

<ul>
    <?php foreach ($collections as $collection): ?>
        <li>
            <strong><?= htmlspecialchars($collection['name']) ?></strong> —
            <code>collection_<?= htmlspecialchars($collection['table_name']) ?></code>
            &nbsp;
            <a href="/collections/<?= urlencode($collection['table_name']) ?>">Открыть</a>
        </li>
    <?php endforeach; ?>
</ul>

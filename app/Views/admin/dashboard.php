<h1>Collections</h1>
<a href="/admin/create">+ Create New Collection</a>

<ul>
    <?php foreach ($collections as $c): ?>
        <li><?= htmlspecialchars($c['name']) ?> (<?= $c['table_name'] ?>)</li>
    <?php endforeach; ?>
</ul>

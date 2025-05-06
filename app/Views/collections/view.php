<h1>Данные коллекции: <?= htmlspecialchars($table) ?></h1>

<?php if (empty($rows)): ?>
    <p>Пока нет данных.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <?php foreach ($fields as $field): ?>
                    <th><?= htmlspecialchars($field) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <?php foreach ($fields as $field): ?>
                        <td><?= htmlspecialchars($row[$field] ?? '') ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="/admin/collections">← Назад к списку</a>

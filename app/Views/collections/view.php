<?php View::start('title') ?>
Коллекция: <?= htmlspecialchars($collectionName) ?>
<?php View::end() ?>

<a href="/collections/<?= htmlspecialchars($collectionName) ?>/add" style="display: inline-block; margin-bottom: 10px; padding: 5px 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Добавить запись</a>

<?php if (empty($data)): ?>
    <p>Нет данных.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <?php foreach (array_keys($data[0]) as $column): ?>
                    <th><?= htmlspecialchars($column) ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?= htmlspecialchars($value) ?></td>
                    <?php endforeach ?>
                    <td><a href= <?php echo "/collections/" . $collectionName . "/edit/" . $row["id"] ?> >Edit</a></td>
                    <td><a href= <?php echo "/collections/" . $collectionName . "/delete/" . $row["id"] ?> >Delete</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

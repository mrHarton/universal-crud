<?php View::start('title') ?>
Коллекция: <?= htmlspecialchars($collectionName) ?>
<?php View::end() ?>

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
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

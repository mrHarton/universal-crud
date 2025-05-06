<h1>Добавить запись в коллекцию: <?= htmlspecialchars($table) ?></h1>

<form method="post">
    <?php foreach ($fields as $field): ?>
        <label>
            <?= htmlspecialchars($field['field_name']) ?>:
            <?php
                $type = strtolower($field['field_type']);
                if ($type === 'date') {
                    echo '<input type="date" name="' . $field['field_name'] . '">';
                } elseif ($type === 'int' || $type === 'integer') {
                    echo '<input type="number" name="' . $field['field_name'] . '">';
                } else {
                    echo '<input type="text" name="' . $field['field_name'] . '">';
                }
            ?>
        </label><br><br>
    <?php endforeach; ?>

    <button type="submit">Сохранить</button>
</form>

<a href="/collections/<?= urlencode($table) ?>/view">← Назад к таблице</a>

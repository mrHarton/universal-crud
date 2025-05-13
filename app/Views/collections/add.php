<h1>Добавить данные в коллекцию: <?= htmlspecialchars($table) ?></h1>

<form method="post" action="/collections/<?= htmlspecialchars($table) ?>/add">
    <?php foreach ($fields as $field): ?>
        <label>
            <?= htmlspecialchars($field['field_name']) ?>:
            <input 
                type="<?= $field['field_type'] === 'INTEGER' ? 'number' : ($field['field_type'] === 'DATE' ? 'date' : 'text') ?>" 
                name="<?= htmlspecialchars($field['field_name']) ?>" 
                required>
        </label>
        <br>
    <?php endforeach; ?>
    <br>
    <button type="submit">Добавить</button>
</form>

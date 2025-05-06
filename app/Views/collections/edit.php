<h1>Редактировать запись в коллекции: <?= htmlspecialchars($table) ?></h1>

<form method="post" action="/collections/<?= urlencode($table) ?>/update/<?= $record['id'] ?>">
    <?php foreach ($fields as $field): ?>
        <label>
            <?= htmlspecialchars($field['field_name']) ?>:
            <input 
                type="text" 
                name="<?= $field['field_name'] ?>" 
                value="<?= htmlspecialchars($record[$field['field_name']] ?? '') ?>"
            >
        </label><br><br>
    <?php endforeach; ?>
    <button type="submit">Сохранить</button>
</form>

<a href="/collections/<?= urlencode($table) ?>/view">← Назад</a>

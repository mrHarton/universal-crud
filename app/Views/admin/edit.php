<?php View::start('title') ?>
Редактирование коллекции
<?php View::end() ?>

<?php if (empty($collection)): ?>
    <p>Нет данных.</p>
<?php else: ?>
    <h1>Редактировать коллекцию</h1>

    <form method="post" action="/admin/store" id="collection-form">
        <label>
            Отображаемое имя:
            <input type="text" name="display_name" required value="<?= $collection['name']?>">
        </label>
        <br>
        <label>
            Техническое имя таблицы:
            <input type="text" name="table_name" required pattern="[a-zA-Z_][a-zA-Z0-9_]*"
                title="Только латиница, цифры и подчёркивание" value="<?= $collection['table_name']?>">
        </label>

        <hr>

        <div id="fields">
            <h3>Поля:</h3>
            <?php foreach ($collection['fields'] as $index => $field): ?>
                <div class="field">
                    <input type="text" name="fields[<?= $index ?>][name]" placeholder="Имя поля" required value="<?= $field['field_name'] ?>">
                    <select name="fields[<?= $index ?>][type]">
                        <option value="TEXT" <?= strtoupper($field['field_type']) === 'TEXT' ? 'selected' : '' ?>>Текст</option>
                        <option value="INTEGER" <?= strtoupper($field['field_type']) === 'INTEGER' ? 'selected' : '' ?>>Число</option>
                        <option value="REAL" <?= strtoupper($field['field_type']) === 'REAL' ? 'selected' : '' ?>>Десятичное</option>
                        <option value="DATE" <?= strtoupper($field['field_type']) === 'DATE' ? 'selected' : '' ?>>Дата</option>
                    </select>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" onclick="addField()">Добавить поле</button>
        <br><br>
        <button type="submit">Сохранить</button>
    </form>

    <script>
        let fieldIndex = <?= count($collection['fields']) ?>;
        function addField() {
            const div = document.createElement('div');
            div.classList.add('field');
            div.innerHTML = `
        <input type="text" name="fields[${fieldIndex}][name]" placeholder="Имя поля" required>
        <select name="fields[${fieldIndex}][type]">
            <option value="TEXT">Текст</option>
            <option value="INTEGER">Число</option>
            <option value="REAL">Десятичное</option>
            <option value="DATE">Дата</option>
        </select>
    `;
            document.getElementById('fields').appendChild(div);
            fieldIndex++;
        }
    </script>
<?php endif ?>
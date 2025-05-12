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
            <div class="field">
                <input type="text" name="fields[0][name]" placeholder="Имя поля" required>
                <select name="fields[0][type]">
                    <option value="TEXT">Текст</option>
                    <option value="INTEGER">Число</option>
                    <option value="REAL">Десятичное</option>
                    <option value="DATE">Дата</option>
                </select>
            </div>
        </div>

        <button type="button" onclick="addField()">Добавить поле</button>
        <br><br>
        <button type="submit">Создать коллекцию</button>
    </form>

    <script>
        let fieldIndex = 1;
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
<?php

class CollectionController
{
    public function view(string $name)
    {
        $pdo = Database::getInstance()->getConnection();

        // Имя таблицы: collection_users, collection_products и т.д.
        $tableName = 'collection_' . preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($name));
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'");
        $exists = $stmt->fetch();

        if (!$exists) {
            http_response_code(404);
            echo "Коллекция не найдена";
            return;
        }

        $data = $pdo->query("SELECT * FROM $tableName")->fetchAll(PDO::FETCH_ASSOC);

        View::render('collections/view', [
            'collectionName' => $name,
            'data' => $data
        ]);
    }

    public function add($table)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $pdo = Database::getInstance()->getConnection();

        // Получим поля из схемы
        $stmt = $pdo->prepare("
        SELECT cf.field_name, cf.field_type 
        FROM collection_fields cf
        JOIN collections c ON c.id = cf.collection_id
        WHERE c.table_name = ?
    ");
        $stmt->execute([$table]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Собираем данные
            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($fields as $field) {
                $name = $field['field_name'];
                $columns[] = $name;
                $placeholders[] = '?';
                $values[] = $_POST[$name] ?? null;
            }

            // Вставка
            $sql = "INSERT INTO collection_$table (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            header("Location: /collections/$table/view");
            exit;
        }

        View::render('collections/add', [
            'table' => $table,
            'fields' => $fields,
        ]);
    }

    public function edit($table, $id)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $id = (int) $id;

        $pdo = Database::getInstance()->getConnection();

        // Получить поля
        $stmt = $pdo->prepare("
        SELECT cf.field_name, cf.field_type 
        FROM collection_fields cf
        JOIN collections c ON c.id = cf.collection_id
        WHERE c.table_name = ?
    ");
        $stmt->execute([$table]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Получить данные записи
        $stmt = $pdo->prepare("SELECT * FROM collection_$table WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            http_response_code(404);
            echo "Запись не найдена";
            return;
        }

        View::render('collections/edit', [
            'table' => $table,
            'fields' => $fields,
            'record' => $record,
        ]);
    }

    public function update($table, $id)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $id = (int) $id;

        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo->prepare("
        SELECT cf.field_name 
        FROM collection_fields cf
        JOIN collections c ON c.id = cf.collection_id
        WHERE c.table_name = ?
    ");
        $stmt->execute([$table]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sets = [];
        $values = [];

        foreach ($fields as $field) {
            $name = $field['field_name'];
            $sets[] = "$name = ?";
            $values[] = $_POST[$name] ?? null;
        }

        $values[] = $id;

        $sql = "UPDATE collection_$table SET " . implode(', ', $sets) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        header("Location: /collections/$table/view");
        exit;
    }

    public function delete($table, $id)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $id = (int) $id;

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("DELETE FROM collection_$table WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: /collections/$table/view");
        exit;
    }

    public function collections()
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT * FROM collections");
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('collections', ['collections' => $collections]);
    }

}

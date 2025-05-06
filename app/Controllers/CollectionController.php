<?php

class CollectionController
{
    public function view($table)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table); // защита

        $pdo = Database::getInstance()->getConnection();

        // Проверим, существует ли таблица
        $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=?");
        $stmt->execute(["collection_" . $table]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo "Коллекция не найдена.";
            return;
        }

        // Получим данные
        $rows = $pdo->query("SELECT * FROM collection_$table")->fetchAll(PDO::FETCH_ASSOC);

        // Получим схему полей из мета-таблицы
        $fieldsStmt = $pdo->prepare("SELECT field_name FROM collection_fields 
                                     JOIN collections ON collections.id = collection_fields.collection_id 
                                     WHERE collections.table_name = ?");
        $fieldsStmt->execute([$table]);
        $fields = $fieldsStmt->fetchAll(PDO::FETCH_COLUMN);

        View::render('collections/view', [
            'table' => $table,
            'rows' => $rows,
            'fields' => $fields,
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

}

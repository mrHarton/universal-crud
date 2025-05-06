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
}

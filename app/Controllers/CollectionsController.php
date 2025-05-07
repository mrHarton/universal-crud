<?php

class CollectionsController
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
}

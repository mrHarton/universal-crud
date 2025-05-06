<?php

class AdminController
{
    public function dashboard()
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo "Доступ запрещён";
            return;
        }
        
        View::render('admin/dashboard');
    }

    public function create()
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo "Доступ запрещён";
            return;
        }
        
        View::render('admin/create');
    }

    public function collections()
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo "Доступ запрещён";
            return;
        }
        
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT * FROM collections");
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        View::render('admin/collections', ['collections' => $collections]);
    }
    

    public function store()
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo "Доступ запрещён";
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($_POST['table_name']));
        $displayName = $_POST['display_name'] ?? '';
        $fields = $_POST['fields'] ?? [];

        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            // Insert collection
            $stmt = $pdo->prepare("INSERT INTO collections (name, table_name) VALUES (?, ?)");
            $stmt->execute([$displayName, $tableName]);
            $collectionId = $pdo->lastInsertId();

            // Insert fields
            $createFields = ["id INTEGER PRIMARY KEY AUTOINCREMENT"];
            foreach ($fields as $field) {
                $name = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($field['name']));
                $type = strtolower($field['type']);

                $pdo->prepare("INSERT INTO collection_fields (collection_id, field_name, field_type) VALUES (?, ?, ?)")
                    ->execute([$collectionId, $name, $type]);

                $sqlType = match ($type) {
                    'string' => 'VARCHAR(255)',
                    'text' => 'TEXT',
                    'int' => 'INTEGER',
                    'date' => 'DATE',
                    default => 'TEXT'
                };
                $createFields[] = "$name $sqlType";
            }

            // Create actual dynamic table
            $sql = "CREATE TABLE IF NOT EXISTS collection_$tableName (" . implode(', ', $createFields) . ")";
            $pdo->exec($sql);

            $pdo->commit();
            header("Location: /admin/dashboard");
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}

<?php

class AdminController {
    public function dashboard() {
        $db = Database::getInstance()->getConnection();
        $collections = $db->query("SELECT * FROM collections")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function create() {
        require __DIR__ . '/../Views/admin/create.php';
    }

    public function store() {
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
                    'text'   => 'TEXT',
                    'int'    => 'INTEGER',
                    'date'   => 'DATE',
                    default  => 'TEXT'
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

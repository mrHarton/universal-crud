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

        $collectionId = $_POST['collection_id'] ?? null; // ID коллекции для обновления (если есть)
        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($_POST['table_name']));
        $displayName = $_POST['display_name'] ?? '';
        $fields = $_POST['fields'] ?? [];

        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            if ($collectionId) {
                // Обновление существующей коллекции
                $stmt = $pdo->prepare("UPDATE collections SET name = ?, table_name = ? WHERE id = ?");
                $stmt->execute([$displayName, $tableName, $collectionId]);

                // Получение текущих полей из базы
                $stmt = $pdo->prepare("SELECT field_name FROM collection_fields WHERE collection_id = ?");
                $stmt->execute([$collectionId]);
                $existingFields = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Удаление старых полей, которых больше нет в запросе
                foreach ($existingFields as $existingField) {
                    $found = false;
                    foreach ($fields as $field) {
                        if ($field['name'] === $existingField) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $pdo->exec("ALTER TABLE collection_$tableName DROP COLUMN $existingField");
                        $pdo->prepare("DELETE FROM collection_fields WHERE collection_id = ? AND field_name = ?")
                            ->execute([$collectionId, $existingField]);
                    }
                }
            } else {
                // Создание новой коллекции
                $stmt = $pdo->prepare("INSERT INTO collections (name, table_name) VALUES (?, ?)");
                $stmt->execute([$displayName, $tableName]);
                $collectionId = $pdo->lastInsertId();

                // Создание таблицы
                $pdo->exec("CREATE TABLE collection_$tableName (id INTEGER PRIMARY KEY AUTOINCREMENT)");
            }

            // Добавление новых полей или обновление существующих
            foreach ($fields as $field) {
                $name = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($field['name']));
                $type = strtolower($field['type']);

                $sqlType = match ($type) {
                    'string' => 'VARCHAR(255)',
                    'text' => 'TEXT',
                    'int' => 'INTEGER',
                    'date' => 'DATE',
                    default => 'TEXT'
                };

                if (!in_array($name, $existingFields)) {
                    // Добавление нового поля
                    $pdo->exec("ALTER TABLE collection_$tableName ADD COLUMN $name $sqlType");
                    $pdo->prepare("INSERT INTO collection_fields (collection_id, field_name, field_type) VALUES (?, ?, ?)")
                        ->execute([$collectionId, $name, $type]);
                } else {
                    // Обновление типа существующего поля
                    $pdo->prepare("UPDATE collection_fields SET field_type = ? WHERE collection_id = ? AND field_name = ?")
                        ->execute([$type, $collectionId, $name]);
                }
            }

            $pdo->commit();
            header("Location: /admin/dashboard");
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    public function edit(string $table_name)
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            http_response_code(403);
            echo "Доступ запрещён";
            return;
        }

        $pdo = Database::getInstance()->getConnection();

        // Подготовка запроса с параметром
        $stmt = $pdo->prepare("SELECT * FROM collections WHERE table_name = :table_name");
        $stmt->execute(['table_name' => $table_name]); // Передача параметра

        // Получение результата
        $collection = $stmt->fetch(PDO::FETCH_ASSOC);

        $collection['id'];

        $stmt = $pdo->prepare("SELECT * FROM collection_fields WHERE collection_id = :collection_id");
        $stmt->execute(['collection_id' => $collection['id']]);

        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $collection['fields'] = $fields;

        View::render('admin/edit', ['collection' => $collection]);
    }
}

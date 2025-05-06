CREATE TABLE collections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    table_name TEXT UNIQUE
);

CREATE TABLE collection_fields (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    collection_id INTEGER,
    field_name TEXT,
    field_type TEXT,
    FOREIGN KEY (collection_id) REFERENCES collections(id)
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('admin', 'user'))
);

INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('john', 'user123', 'user');

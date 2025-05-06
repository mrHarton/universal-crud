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

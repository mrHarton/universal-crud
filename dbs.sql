CREATE TABLE collections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,         -- Display name (e.g. "Articles")
    table_name TEXT NOT NULL UNIQUE    -- DB table name (e.g. "collection_articles")
);

CREATE TABLE collection_fields (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    collection_id INTEGER NOT NULL,
    field_name TEXT NOT NULL,         -- e.g., "title"
    field_type TEXT NOT NULL,         -- string, int, date, etc.
    FOREIGN KEY (collection_id) REFERENCES collections(id)
);

<?php
require_once __DIR__ . '/app/config/database.php';

$db = getDatabaseConnection();

// Создание таблицы users
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        role TEXT NOT NULL DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// Создание таблицы terms (без category)
$db->exec("
    CREATE TABLE IF NOT EXISTS terms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        definition TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT 'active',
        created_by INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    )
");

// Создание таблицы term_suggestions (без category)
$db->exec("
    CREATE TABLE IF NOT EXISTS term_suggestions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        definition TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT 'pending',
        created_by INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    )
");

// Создание таблицы quizzes
$db->exec("
    CREATE TABLE IF NOT EXISTS quizzes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        user_id INTEGER,
        is_public INTEGER NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )
");

// Создание таблицы quiz_terms
$db->exec("
    CREATE TABLE IF NOT EXISTS quiz_terms (
        quiz_id INTEGER,
        term_id INTEGER,
        PRIMARY KEY (quiz_id, term_id),
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id),
        FOREIGN KEY (term_id) REFERENCES terms(id)
    )
");

// Создание таблицы quiz_results
$db->exec("
    CREATE TABLE IF NOT EXISTS quiz_results (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        quiz_id INTEGER,
        score INTEGER NOT NULL,
        total_questions INTEGER NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
    )
");

// Очистка терминов и предложений
$db->exec("DELETE FROM terms");
$db->exec("DELETE FROM term_suggestions");

// Добавление тестового админа
$stmt = $db->prepare("INSERT OR IGNORE INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', password_hash('admin123', PASSWORD_BCRYPT), 'admin@example.com', 'admin']);

// Добавление тестовых терминов (без category)
$stmt = $db->prepare("INSERT OR IGNORE INTO terms (title, definition, created_by) VALUES (?, ?, ?)");
$stmt->execute(['API', 'Application Programming Interface', 1]);
$stmt->execute(['Scrum', 'Agile project management framework', 1]);
$stmt->execute(['TestTermen', 'TestTermen', 1]);

echo "Database initialized successfully.";
?>
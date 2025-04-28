<?php
/**
 * Подключение к базе данных SQLite.
 * @return PDO
 */
function getDatabaseConnection() {
    $db = new PDO('sqlite:' . __DIR__ . '/../../data/jobvocab.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
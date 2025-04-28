<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Модель для работы с пользователями.
 */
class User {
    private static $db;

    /**
     * Инициализация подключения к БД.
     */
    public static function init() {
        self::$db = getDatabaseConnection();
    }

    /**
     * Поиск пользователя по имени.
     * @param string $username
     * @return array|null
     */
    public static function findByUsername($username) {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Получение всех пользователей.
     * @return array
     */
    public static function getAll() {
        $stmt = self::$db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создание нового пользователя.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $role
     */
    public static function create($username, $password, $email, $role = 'user') {
        $stmt = self::$db->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $password, $email, $role]);
    }

    /**
     * Удаление пользователя.
     * @param int $id
     */
    public static function delete($id) {
        $stmt = self::$db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
}
User::init();
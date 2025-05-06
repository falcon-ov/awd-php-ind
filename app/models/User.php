<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private static $db;

    /**
     * Инициализирует подключение к базе данных.
     *
     * @return void
     */
    public static function init() {
        self::$db = getDatabaseConnection();
    }

    /**
     * Находит пользователя по имени пользователя.
     *
     * @param string $username Имя пользователя
     * @return array|null Ассоциативный массив с данными пользователя или null, если пользователь не найден
     */
    public static function findByUsername($username) {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Получает список всех пользователей.
     *
     * @return array Массив пользователей, отсортированных по дате создания
     */
    public static function getAll() {
        $stmt = self::$db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создает нового пользователя.
     *
     * @param string $username Имя пользователя
     * @param string $password Хешированный пароль
     * @param string $email Электронная почта
     * @param string $role Роль пользователя (по умолчанию 'user')
     * @return void
     */
    public static function create($username, $password, $email, $role = 'user') {
        $stmt = self::$db->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $password, $email, $role]);
    }

    /**
     * Удаляет пользователя по ID.
     *
     * @param int $id ID пользователя
     * @return void
     */
    public static function delete($id) {
        $stmt = self::$db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
}
User::init();
?>
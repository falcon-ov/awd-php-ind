<?php
require_once __DIR__ . '/../config/database.php';

class Term {
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
     * Создает новый термин.
     *
     * @param string $title Название термина
     * @param string $definition Определение термина
     * @param string $status Статус термина
     * @param int $created_by ID пользователя, создавшего термин
     * @return void
     */
    public static function create($title, $definition, $status, $created_by) {
        $stmt = self::$db->prepare('INSERT INTO terms (title, definition, status, created_by) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $definition, $status, $created_by]);
    }

    /**
     * Обновляет существующий термин.
     *
     * @param int $id ID термина
     * @param string $title Название термина
     * @param string $definition Определение термина
     * @param string $status Статус термина
     * @return void
     */
    public static function update($id, $title, $definition, $status) {
        $stmt = self::$db->prepare('UPDATE terms SET title = ?, definition = ?, status = ? WHERE id = ?');
        $stmt->execute([$title, $definition, $status, $id]);
    }

    /**
     * Удаляет термин.
     *
     * @param int $id ID термина
     * @return void
     */
    public static function delete($id) {
        $stmt = self::$db->prepare('DELETE FROM terms WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Получает все термины, отсортированные по названию.
     *
     * @return array Массив терминов
     */
    public static function getAll() {
        $stmt = self::$db->query('SELECT * FROM terms ORDER BY title');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получает термины, созданные указанным пользователем.
     *
     * @param int $user_id ID пользователя
     * @return array Массив терминов
     */
    public static function getByUser($user_id) {
        $stmt = self::$db->prepare('SELECT * FROM terms WHERE created_by = ? ORDER BY title');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Выполняет поиск терминов по названию.
     *
     * @param string $search Поисковый запрос
     * @return array Массив терминов
     */
    public static function search($search) {
        try {
            $query = 'SELECT * FROM terms WHERE 1=1';
            $params = [];
            if ($search) {
                $query .= ' AND title LIKE ?';
                $params[] = '%' . $search . '%';
            }
            $query .= ' ORDER BY title';
            $stmt = self::$db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Получает случайные термины в указанном количестве.
     *
     * @param int $limit Количество терминов
     * @return array Массив терминов
     */
    public static function getRandom($limit) {
        $stmt = self::$db->query("SELECT * FROM terms ORDER BY RANDOM() LIMIT $limit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создает предложение нового термина.
     *
     * @param string $title Название термина
     * @param string $definition Определение термина
     * @param int $created_by ID пользователя, предложившего термин
     * @return void
     */
    public static function suggest($title, $definition, $created_by) {
        $stmt = self::$db->prepare('INSERT INTO term_suggestions (title, definition, status, created_by) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $definition, 'pending', $created_by]);
    }

    /**
     * Получает все предложенные термины со статусом "pending".
     *
     * @return array Массив предложений
     */
    public static function getSuggestions() {
        $stmt = self::$db->query('SELECT ts.*, u.username FROM term_suggestions ts JOIN users u ON ts.created_by = u.id WHERE ts.status = "pending" ORDER BY ts.created_at');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Принимает предложенный термин и добавляет его в таблицу терминов.
     *
     * @param int $id ID предложения
     * @param string $status Статус термина
     * @param int $admin_id ID администратора
     * @return void
     */
    public static function acceptSuggestion($id, $status, $admin_id) {
        $stmt = self::$db->prepare('SELECT * FROM term_suggestions WHERE id = ?');
        $stmt->execute([$id]);
        $suggestion = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($suggestion) {
            self::create($suggestion['title'], $suggestion['definition'], $status, $admin_id);
            $stmt = self::$db->prepare('DELETE FROM term_suggestions WHERE id = ?');
            $stmt->execute([$id]);
        }
    }

    /**
     * Удаляет предложенный термин.
     *
     * @param int $id ID предложения
     * @return void
     */
    public static function deleteSuggestion($id) {
        $stmt = self::$db->prepare('DELETE FROM term_suggestions WHERE id = ?');
        $stmt->execute([$id]);
    }
}
Term::init();
?>
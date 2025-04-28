<?php
require_once __DIR__ . '/../config/database.php';

class Quiz {
    private static $db;

    public static function init() {
        self::$db = getDatabaseConnection();
    }

    public static function create($title, $user_id, $is_public, $term_ids) {
        $stmt = self::$db->prepare('INSERT INTO quizzes (title, user_id, is_public) VALUES (?, ?, ?)');
        $stmt->execute([$title, $user_id, $is_public]);
        $quiz_id = self::$db->lastInsertId();
        foreach ($term_ids as $term_id) {
            $stmt = self::$db->prepare('INSERT INTO quiz_terms (quiz_id, term_id) VALUES (?, ?)');
            $stmt->execute([$quiz_id, $term_id]);
        }
    }

    public static function delete($id) {
        $stmt = self::$db->prepare('DELETE FROM quiz_terms WHERE quiz_id = ?');
        $stmt->execute([$id]);
        $stmt = self::$db->prepare('DELETE FROM quiz_results WHERE quiz_id = ?');
        $stmt->execute([$id]);
        $stmt = self::$db->prepare('DELETE FROM quizzes WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function deleteByUser($id, $user_id) {
        $stmt = self::$db->prepare('DELETE FROM quiz_terms WHERE quiz_id = ? AND quiz_id IN (SELECT id FROM quizzes WHERE user_id = ?)');
        $stmt->execute([$id, $user_id]);
        $stmt = self::$db->prepare('DELETE FROM quiz_results WHERE quiz_id = ? AND quiz_id IN (SELECT id FROM quizzes WHERE user_id = ?)');
        $stmt->execute([$id, $user_id]);
        $stmt = self::$db->prepare('DELETE FROM quizzes WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $user_id]);
    }

    public static function getAll() {
        $stmt = self::$db->query('SELECT * FROM quizzes ORDER BY title');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllPublic() {
        $stmt = self::$db->prepare('SELECT * FROM quizzes WHERE is_public = 1 OR user_id = ? ORDER BY title');
        $stmt->execute([$_SESSION['user_id'] ?? 0]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTerms($quiz_id) {
        $stmt = self::$db->prepare('SELECT t.* FROM terms t JOIN quiz_terms qt ON t.id = qt.term_id WHERE qt.quiz_id = ?');
        $stmt->execute([$quiz_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
Quiz::init();
?>
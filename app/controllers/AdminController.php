<?php
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../utils/validate.php';

class AdminController {
    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdmin();
        $db = getDatabaseConnection();
        $termCount = $db->query('SELECT COUNT(*) FROM terms')->fetchColumn();
        $userCount = $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $quizCount = $db->query('SELECT COUNT(*) FROM quizzes')->fetchColumn();
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function manageTerms() {
        $this->checkAdmin();
        $terms = Term::getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_term'])) {
            $term_id = (int)$_POST['term_id'];
            Term::delete($term_id);
            $_SESSION['message'] = 'Термин удалён успешно.';
            header('Location: /admin/terms');
            exit;
        }
        require __DIR__ . '/../views/admin/terms.php';
    }

    public function manageUsers() {
        $this->checkAdmin();
        $db = getDatabaseConnection();
        $stmt = $db->query('SELECT id, username, email, role FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
            $user_id = (int)$_POST['user_id'];
            if ($user_id !== $_SESSION['user_id']) {
                $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$user_id]);
                $_SESSION['message'] = 'Пользователь удалён успешно.';
            } else {
                $_SESSION['message'] = 'Нельзя удалить самого себя.';
            }
            header('Location: /admin/users');
            exit;
        }
        require __DIR__ . '/../views/admin/users.php';
    }

    public function manageQuizzes() {
        $this->checkAdmin();
        $quizzes = Quiz::getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz'])) {
            $quiz_id = (int)$_POST['quiz_id'];
            Quiz::delete($quiz_id);
            $_SESSION['message'] = 'Квиз удалён успешно.';
            header('Location: /admin/quizzes');
            exit;
        }
        require __DIR__ . '/../views/admin/quizzes.php';
    }

    public function manageSuggestions() {
        $this->checkAdmin();
        $suggestions = Term::getSuggestions();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $suggestion_id = (int)$_POST['suggestion_id'];
            if (isset($_POST['approve'])) {
                Term::approveSuggestion($suggestion_id);
                $_SESSION['message'] = 'Предложение одобрено и добавлено как термин.';
            } elseif (isset($_POST['reject'])) {
                Term::rejectSuggestion($suggestion_id);
                $_SESSION['message'] = 'Предложение отклонено.';
            }
            header('Location: /admin/suggestions');
            exit;
        }
        require __DIR__ . '/../views/admin/suggestions.php';
    }
}
?>
<?php
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/validate.php';
require_once __DIR__ . '/../utils/hash.php';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $title = validateInput($_POST['title']);
                $definition = validateInput($_POST['definition']);
                $status = validateInput($_POST['status']);
                if (!in_array($status, ['active', 'inactive'])) {
                    $_SESSION['message'] = 'Ошибка: Неверный статус термина.';
                } else {
                    Term::create($title, $definition, $status, $_SESSION['user_id']);
                    $_SESSION['message'] = 'Термин создан успешно.';
                }
                header('Location: /admin/terms');
                exit;
            }
            if (isset($_POST['update'])) {
                $term_id = (int)$_POST['id'];
                $title = validateInput($_POST['title']);
                $definition = validateInput($_POST['definition']);
                $status = validateInput($_POST['status']);
                if (!in_array($status, ['active', 'inactive'])) {
                    $_SESSION['message'] = 'Ошибка: Неверный статус термина.';
                } else {
                    Term::update($term_id, $title, $definition, $status);
                    $_SESSION['message'] = 'Термин обновлён успешно.';
                }
                header('Location: /admin/terms');
                exit;
            }
            if (isset($_POST['delete'])) {
                $term_id = (int)$_POST['id'];
                Term::delete($term_id);
                $_SESSION['message'] = 'Термин удалён успешно.';
                header('Location: /admin/terms');
                exit;
            }
        }
        require __DIR__ . '/../views/admin/terms.php';
    }

    public function manageUsers() {
        $this->checkAdmin();
        $db = getDatabaseConnection();
        $stmt = $db->query('SELECT id, username, email, role FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $username = validateInput($_POST['username']);
                $email = validateInput($_POST['email']);
                $password = $_POST['password'];
                $role = validateInput($_POST['role']);
                
                if (empty($username) || empty($email) || empty($password) || empty($role)) {
                    $_SESSION['message'] = 'Ошибка: Все поля обязательны.';
                } elseif (!in_array($role, ['user', 'admin'])) {
                    $_SESSION['message'] = 'Ошибка: Неверная роль пользователя.';
                } elseif (User::findByUsername($username)) {
                    $_SESSION['message'] = 'Ошибка: Пользователь с таким именем уже существует.';
                } else {
                    $hashedPassword = hashPassword($password);
                    try {
                        User::create($username, $hashedPassword, $email, $role);
                        $_SESSION['message'] = 'Пользователь создан успешно.';
                    } catch (PDOException $e) {
                        $_SESSION['message'] = 'Ошибка: Не удалось создать пользователя. Возможно, email уже используется.';
                    }
                }
                header('Location: /admin/users');
                exit;
            }
            if (isset($_POST['delete_user'])) {
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
        }
        require __DIR__ . '/../views/admin/users.php';
    }

    public function manageQuizzes() {
        $this->checkAdmin();
        $quizzes = Quiz::getAll();
        $terms = Term::getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete'])) {
                $quiz_id = (int)$_POST['id'];
                Quiz::delete($quiz_id);
                $_SESSION['message'] = 'Квиз удалён успешно.';
                header('Location: /admin/quizzes');
                exit;
            }
            if (isset($_POST['create'])) {
                $title = validateInput($_POST['title']);
                $term_ids = isset($_POST['term_ids']) ? array_map('intval', $_POST['term_ids']) : [];
                Quiz::create($title, $_SESSION['user_id'], 1, $term_ids);
                $_SESSION['message'] = 'Квиз создан успешно.';
                header('Location: /admin/quizzes');
                exit;
            }
        }
        require __DIR__ . '/../views/admin/quizzes.php';
    }

    public function manageSuggestions() {
        $this->checkAdmin();
        $suggestions = Term::getSuggestions();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['suggestion_id'])) {
                $_SESSION['message'] = 'Ошибка: ID предложения не указан.';
                header('Location: /admin/suggestions');
                exit;
            }
            $suggestion_id = (int)$_POST['suggestion_id'];
            if (isset($_POST['approve'])) {
                $status = validateInput($_POST['status']);
                Term::acceptSuggestion($suggestion_id, $status, $_SESSION['user_id']);
                $_SESSION['message'] = 'Предложение одобрено и добавлено как термин.';
            } elseif (isset($_POST['reject'])) {
                Term::deleteSuggestion($suggestion_id);
                $_SESSION['message'] = 'Предложение отклонено.';
            }
            header('Location: /admin/suggestions');
            exit;
        }
        require __DIR__ . '/../views/admin/suggestions.php';
    }
}
?>
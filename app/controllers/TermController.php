<?php
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../utils/validate.php';

class TermController {
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function suggest() {
        $this->checkAuth();
        if ($_SESSION['role'] === 'admin') {
            $_SESSION['message'] = 'Администраторы не могут предлагать термины.';
            header('Location: /');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = validateInput($_POST['title']);
            $definition = validateInput($_POST['definition']);
            Term::suggest($title, $definition, $_SESSION['user_id']);
            $_SESSION['message'] = 'Предложение термина отправлено на проверку.';
            header('Location: /term/suggest');
            exit;
        }
        require __DIR__ . '/../views/term/suggest.php';
    }

    public function list() {
        $this->checkAuth();
        $terms = Term::getByUser($_SESSION['user_id']);
        require __DIR__ . '/../views/term/list.php';
    }
}
?>
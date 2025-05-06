<?php
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../utils/validate.php';

class PublicController {

    /**
     * Отображает главную страницу с списком всех терминов.
     *
     * @return void
     */
    public function index() {
        $terms = Term::getAll();
        require __DIR__ . '/../views/public/index.php';
    }

    /**
     * Обрабатывает поиск терминов по запросу.
     *
     * @return void
     */
    public function search() {
        $search = isset($_GET['search']) ? validateInput($_GET['search']) : '';
        $terms = Term::search($search);
        require __DIR__ . '/../views/public/search.php';
    }

    /**
     * Управляет процессом прохождения квиза.
     * Обрабатывает выбор квиза, ответы пользователя, удаление квиза и отображение вопросов.
     *
     * @return void
     */
    public function quiz() {
        $quizzes = Quiz::getAllPublic();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'])) {
            $quiz_id = (int)$_POST['quiz_id'];
            $terms = Quiz::getTerms($quiz_id);
            if ($terms) {
                shuffle($terms);
                $_SESSION['quiz'] = [
                    'id' => $quiz_id,
                    'terms' => array_slice($terms, 0, min(10, count($terms))),
                    'current' => 0,
                    'score' => 0
                ];
                header('Location: /quiz');
                exit;
            } else {
                $_SESSION['message'] = 'В выбранном квизе нет терминов.';
                header('Location: /quiz');
                exit;
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz'])) {
            $quiz_id = (int)$_POST['quiz_id'];
            Quiz::deleteByUser($quiz_id, $_SESSION['user_id']);
            $_SESSION['message'] = 'Квиз удалён успешно.';
            header('Location: /quiz');
            exit;
        }
        if (isset($_SESSION['quiz'])) {
            $quiz = $_SESSION['quiz'];
            $terms = $quiz['terms'];
            $current = $quiz['current'];
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
                $answer = validateInput($_POST['answer']);
                if ($answer === $terms[$current]['definition']) {
                    $_SESSION['quiz']['score']++;
                }
                $_SESSION['quiz']['current']++;
                if ($_SESSION['quiz']['current'] >= count($terms)) {
                    $this->saveQuizResult($quiz['id'], $_SESSION['quiz']['score'], count($terms));
                    unset($_SESSION['quiz']);
                    header('Location: /quiz/result');
                    exit;
                }
                header('Location: /quiz');
                exit;
            }
            if ($current < count($terms)) {
                $term = $terms[$current];
                $answers = [$term['definition']];
                $other_terms = Term::getRandom(3);
                foreach ($other_terms as $other) {
                    if ($other['definition'] !== $term['definition']) {
                        $answers[] = $other['definition'];
                    }
                }
                shuffle($answers);
                require __DIR__ . '/../views/public/quiz.php';
                return;
            } else {
                unset($_SESSION['quiz']);
                $_SESSION['message'] = 'Квиз завершён или данные некорректны.';
                header('Location: /quiz');
                exit;
            }
        }
        require __DIR__ . '/../views/public/quiz_select.php';
    }

    /**
     * Обрабатывает создание нового квиза авторизованным пользователем.
     *
     * @return void
     */
    public function createQuiz() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = validateInput($_POST['title']);
            $is_public = ($_SESSION['role'] === 'admin' && isset($_POST['is_public'])) ? 1 : 0;
            $term_ids = isset($_POST['term_ids']) ? array_map('intval', $_POST['term_ids']) : [];
            Quiz::create($title, $_SESSION['user_id'], $is_public, $term_ids);
            $_SESSION['message'] = 'Квиз создан успешно.';
            header('Location: /quiz');
            exit;
        }
        $terms = Term::getAll();
        require __DIR__ . '/../views/public/quiz_create.php';
    }

    /**
     * Отображает результаты последнего пройденного квиза.
     *
     * @return void
     */
    public function quizResult() {
        if (!isset($_SESSION['quiz_result'])) {
            $_SESSION['message'] = 'Результаты квиза недоступны.';
            header('Location: /quiz');
            exit;
        }
        $result = $_SESSION['quiz_result'];
        unset($_SESSION['quiz_result']);
        require __DIR__ . '/../views/public/quiz_result.php';
    }

    /**
     * Сохраняет результаты квиза в базе данных и сессии.
     *
     * @param int $quiz_id ID квиза
     * @param int $score Количество правильных ответов
     * @param int $total Общее количество вопросов
     * @return void
     */
    private function saveQuizResult($quiz_id, $score, $total) {
        if (isset($_SESSION['user_id'])) {
            $db = getDatabaseConnection();
            $stmt = $db->prepare('INSERT INTO quiz_results (user_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $quiz_id, $score, $total]);
        }
        $_SESSION['quiz_result'] = ['score' => $score, 'total' => $total];
    }
}
?>
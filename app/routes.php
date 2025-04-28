<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PublicController.php';
require_once __DIR__ . '/controllers/TermController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/' || $uri === '/index.php') {
    (new PublicController())->index();
} elseif ($uri === '/search') {
    (new PublicController())->search();
} elseif ($uri === '/quiz') {
    (new PublicController())->quiz();
} elseif ($uri === '/quiz/create') {
    (new PublicController())->createQuiz();
} elseif ($uri === '/quiz/result') {
    (new PublicController())->quizResult();
} elseif ($uri === '/login') {
    (new AuthController())->login();
} elseif ($uri === '/register') {
    (new AuthController())->register();
} elseif ($uri === '/logout') {
    (new AuthController())->logout();
} elseif ($uri === '/term/suggest') {
    (new TermController())->suggest();
} elseif ($uri === '/term/list') {
    (new TermController())->list();
} elseif ($uri === '/admin/dashboard') {
    (new AdminController())->dashboard();
} elseif ($uri === '/admin/terms') {
    (new AdminController())->manageTerms();
} elseif ($uri === '/admin/users') {
    (new AdminController())->manageUsers();
} elseif ($uri === '/admin/quizzes') {
    (new AdminController())->manageQuizzes();
} elseif ($uri === '/admin/suggestions') {
    (new AdminController())->manageSuggestions();
} else {
    http_response_code(404);
    echo "404 - Страница не найдена";
}
?>
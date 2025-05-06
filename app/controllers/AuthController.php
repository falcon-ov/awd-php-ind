<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/hash.php';
require_once __DIR__ . '/../utils/validate.php';

/**
 * Class AuthController
 * 
 * Handles user authentication operations such as login, registration, and logout.
 */
class AuthController {

    /**
     * Обрабатывает вход пользователя в систему.
     * 
     * Проверяет учетные данные пользователя и начинает сессию, если данные верны.
     * Если метод запроса не POST, отображает страницу логина.
     * 
     * @return void
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = validateInput($_POST['username']);
            $password = $_POST['password'];
            $user = User::findByUsername($username);
            if ($user && verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: /');
                exit;
            } else {
                $error = "Неверный логин или пароль";
                require __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require __DIR__ . '/../views/auth/login.php';
        }
    }

    /**
     * Обрабатывает регистрацию пользователя.
     * 
     * Проверяет входные данные и создает нового пользователя, если имя не занято.
     * Если метод запроса не POST, отображает страницу регистрации.
     * 
     * @return void
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = validateInput($_POST['username']);
            $email = validateInput($_POST['email']);
            $password = hashPassword($_POST['password']);
            if (User::findByUsername($username)) {
                $error = "Пользователь уже существует";
                require __DIR__ . '/../views/auth/register.php';
            } else {
                User::create($username, $password, $email);
                header('Location: /login');
                exit;
            }
        } else {
            require __DIR__ . '/../views/auth/register.php';
        }
    }

    /**
     * Выполняет выход пользователя из системы.
     * 
     * Уничтожает текущую сессию и перенаправляет на главную страницу.
     * 
     * @return void
     */
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
?>
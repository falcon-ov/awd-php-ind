<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>JobVocab</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>JobVocab</h1>
        <nav>
            <a href="/">Главная</a>
            <a href="/search">Поиск</a>
            <a href="/quiz">Квиз</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'user'): ?>
                    <a href="/term/suggest">Предложить термин</a>
                <?php endif; ?>
                <a href="/term/list">Мои термины</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/admin/dashboard">Админ-панель</a>
                    <a href="/admin/suggestions">Предложения терминов</a>
                <?php endif; ?>
                <a href="/logout">Выйти</a>
            <?php else: ?>
                <a href="/login">Вход</a>
                <a href="/register">Регистрация</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php include $content; ?>
    </main>
    <footer>
        <p>© 2025 JobVocab</p>
    </footer>
</body>
</html>
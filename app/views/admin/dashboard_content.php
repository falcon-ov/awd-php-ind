<div class="container">
    <h2>Админ-панель: Статистика</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <div class="card">
        <h3>Термины</h3>
        <p>Всего терминов: <?php echo htmlspecialchars($termCount); ?></p>
        <a href="/admin/terms" class="btn">Управление терминами</a>
    </div>
    <div class="card">
        <h3>Пользователи</h3>
        <p>Всего пользователей: <?php echo htmlspecialchars($userCount); ?></p>
        <a href="/admin/users" class="btn">Управление пользователями</a>
    </div>
    <div class="card">
        <h3>Квизы</h3>
        <p>Всего квизов: <?php echo htmlspecialchars($quizCount); ?></p>
        <a href="/admin/quizzes" class="btn">Управление квизами</a>
    </div>
</div>
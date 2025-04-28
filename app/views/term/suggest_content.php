<div class="container">
    <h2>Предложить термин</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="/term/suggest">
        <label>Название: <input type="text" name="title" required></label>
        <label>Определение: <textarea name="definition" required></textarea></label>
        <button type="submit">Отправить на проверку</button>
    </form>
</div>
?>
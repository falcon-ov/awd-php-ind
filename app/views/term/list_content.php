<div class="container">
    <h2>Мои термины</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <?php if ($_SESSION['role'] === 'user'): ?>
        <a href="/term/suggest" class="btn">Предложить термин</a>
    <?php endif; ?>
    <div class="terms">
        <?php if (empty($terms)): ?>
            <p>У вас нет терминов.</p>
        <?php else: ?>
            <?php foreach ($terms as $term): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($term['title']); ?></h3>
                    <p><?php echo htmlspecialchars($term['definition']); ?></p>
                    <p><strong>Статус:</strong> <?php echo $term['status'] === 'active' ? 'Активен' : 'Неактивен'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div class="container">
    <h2>Создать квиз</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="/quiz/create">
        <input type="hidden" name="create" value="1">
        <label for="title">Название квиза:</label>
        <input type="text" id="title" name="title" required>
        <h3>Выберите термины:</h3>
        <?php if (empty($terms)): ?>
            <p>Нет доступных терминов для создания квиза.</p>
        <?php else: ?>
            <?php foreach ($terms as $term): ?>
                <div class="term-item">
                    <label>
                        <input type="checkbox" name="term_ids[]" value="<?php echo $term['id']; ?>">
                        <strong><?php echo htmlspecialchars($term['title']); ?></strong>
                    </label>
                    <p class="term-definition"><?php echo htmlspecialchars(substr($term['definition'], 0, 100)) . (strlen($term['definition']) > 100 ? '...' : ''); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <button type="submit">Создать</button>
    </form>
</div>
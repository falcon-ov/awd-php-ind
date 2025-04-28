<div class="container">
    <h2>Поиск терминов</h2>
    <form method="GET" action="/search">
        <label>Поиск: <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>"></label>
        <label>Категория:
            <select name="category">
                <option value="">Все</option>
                <option value="IT" <?php if (($category ?? '') === 'IT') echo 'selected'; ?>>IT</option>
                <option value="Маркетинг" <?php if (($category ?? '') === 'Маркетинг') echo 'selected'; ?>>Маркетинг</option>
                <option value="Финансы" <?php if (($category ?? '') === 'Финансы') echo 'selected'; ?>>Финансы</option>
            </select>
        </label>
        <button type="submit">Найти</button>
    </form>
    <div class="terms">
        <?php if (empty($terms)): ?>
            <p>Термины не найдены.</p>
        <?php else: ?>
            <?php foreach ($terms as $term): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($term['title']); ?></h3>
                    <p><?php echo htmlspecialchars($term['definition']); ?></p>
                    <p><strong>Категория:</strong> <?php echo htmlspecialchars($term['category']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

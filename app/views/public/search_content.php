<div class="container">
    <h2>Поиск терминов</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="GET" action="/search">
        <label>Поиск: <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>"></label>
        <button type="submit">Найти</button>
    </form>
    <div class="terms">
        <?php if (!isset($terms) || empty($terms)): ?>
            <p>Термины не найдены.</p>
        <?php else: ?>
            <?php foreach ($terms as $term): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($term['title']); ?></h3>
                    <p><?php echo htmlspecialchars($term['definition']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

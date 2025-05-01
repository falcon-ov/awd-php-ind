<div class="container">
    <h2>Создать квиз</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="/quiz/create">
        <label for="title">Название квиза:</label>
        <input type="text" id="title" name="title" required>
        <?php if ($is_admin): ?>
            <label>
                <input type="checkbox" name="is_public" value="1">
                Общедоступный квиз
            </label><br>
        <?php endif; ?>
        <h3>Выберите термины:</h3>
        <?php foreach ($terms as $term): ?>
            <label>
                <input type="checkbox" name="term_ids[]" value="<?php echo $term['id']; ?>">
                <?php echo htmlspecialchars($term['title']); ?> (<?php echo htmlspecialchars($term['category']); ?>)
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Создать</button>
    </form>
</div>

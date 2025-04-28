<div class="container">
    <h2>Квиз</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <p>Вопрос <?php echo $current + 1; ?> из <?php echo count($terms); ?></p>
    <h3><?php echo htmlspecialchars($term['title']); ?></h3>
    <form method="POST">
        <?php foreach ($answers as $answer): ?>
            <label>
                <input type="radio" name="answer" value="<?php echo htmlspecialchars($answer); ?>" required>
                <?php echo htmlspecialchars($answer); ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Ответить</button>
    </form>
</div>
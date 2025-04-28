<div class="container">
    <h2>Словарь профессий</h2>
    <div class="terms">
        <?php foreach ($terms as $term): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($term['title']); ?></h3>
                <p><?php echo htmlspecialchars($term['definition']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
?>
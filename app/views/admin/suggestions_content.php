<div class="container">
    <h2>Предложения терминов</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <h3>Список предложений</h3>
    <?php if (empty($suggestions)): ?>
        <p>Нет предложений терминов.</p>
    <?php else: ?>
        <table>
            <tr><th>Название</th><th>Определение</th><th>Предложил</th><th>Дата</th><th>Действия</th></tr>
            <?php foreach ($suggestions as $suggestion): ?>
                <tr>
                    <td><?php echo htmlspecialchars($suggestion['title']); ?></td>
                    <td><?php echo htmlspecialchars($suggestion['definition']); ?></td>
                    <td><?php echo htmlspecialchars($suggestion['username']); ?></td>
                    <td><?php echo htmlspecialchars($suggestion['created_at']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="accept" value="1">
                            <input type="hidden" name="id" value="<?php echo $suggestion['id']; ?>">
                            <select name="status" required>
                                <option value="active">Активен</option>
                                <option value="inactive">Неактивен</option>
                            </select>
                            <button type="submit">Принять</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="id" value="<?php echo $suggestion['id']; ?>">
                            <button type="submit">Отклонить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

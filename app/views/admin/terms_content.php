<div class="container">
    <h2>Управление терминами</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <h3>Создать термин</h3>
    <form method="POST">
        <input type="hidden" name="create" value="1">
        <label>Название: <input type="text" name="title" required></label>
        <label>Определение: <textarea name="definition" required></textarea></label>
        <label>Статус:
            <input type="radio" name="status" value="active" checked> Активен
            <input type="radio" name="status" value="inactive"> Неактивен
        </label>
        <button type="submit">Создать</button>
    </form>
    <h3>Список терминов</h3>
    <table>
        <tr><th>Название</th><th>Определение</th><th>Статус</th><th>Действия</th></tr>
        <?php foreach ($terms as $term): ?>
            <tr>
                <td><?php echo htmlspecialchars($term['title']); ?></td>
                <td><?php echo htmlspecialchars($term['definition']); ?></td>
                <td><?php echo $term['status'] === 'active' ? 'Активен' : 'Неактивен'; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="update" value="1">
                        <input type="hidden" name="id" value="<?php echo $term['id']; ?>">
                        <input type="text" name="title" value="<?php echo htmlspecialchars($term['title']); ?>" required>
                        <textarea name="definition" required><?php echo htmlspecialchars($term['definition']); ?></textarea>
                        <select name="status" required>
                            <option value="active" <?php if ($term['status'] === 'active') echo 'selected'; ?>>Активен</option>
                            <option value="inactive" <?php if ($term['status'] === 'inactive') echo 'selected'; ?>>Неактивен</option>
                        </select>
                        <button type="submit">Обновить</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete" value="1">
                        <input type="hidden" name="id" value="<?php echo $term['id']; ?>">
                        <button type="submit">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

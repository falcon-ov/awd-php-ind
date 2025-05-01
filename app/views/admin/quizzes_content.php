<div class="container">
    <h2>Управление квизами</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <h3>Создать квиз</h3>
    <form method="POST" action="/admin/quizzes">
        <input type="hidden" name="create" value="1">
        <label for="title">Название квиза:</label>
        <input type="text" id="title" name="title" required>
        <h3>Выберите термины:</h3>
        <?php foreach ($terms as $term): ?>
            <label>
                <input type="checkbox" name="term_ids[]" value="<?php echo $term['id']; ?>">
                <?php echo htmlspecialchars($term['title']); ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Создать</button>
    </form>
    <h3>Список квизов</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Создатель</th>
                <th>Тип</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?php echo htmlspecialchars($quiz['id']); ?></td>
                    <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                    <td>
                        <?php
                        $creator = User::findByUsername($quiz['user_id']);
                        echo htmlspecialchars($creator['username'] ?? 'Неизвестно');
                        ?>
                    </td>
                    <td><?php echo $quiz['is_public'] ? 'Общедоступный' : 'Личный'; ?></td>
                    <td>
                        <form method="POST" action="/admin/quizzes" style="display:inline;">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="id" value="<?php echo $quiz['id']; ?>">
                            <button type="submit" class="error" onclick="return confirm('Удалить квиз <?php echo htmlspecialchars($quiz['title']); ?>?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

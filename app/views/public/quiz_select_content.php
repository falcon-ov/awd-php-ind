<div class="container">
    <h2>Выберите квиз</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="card" style="background: #dff0d8; color: #3c763d;">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label>Квиз:
            <select name="quiz_id" required>
                <?php foreach ($quizzes as $quiz): ?>
                    <option value="<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Начать</button>
    </form>
    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Ваши квизы</h3>
        <?php
        $user_quizzes = array_filter($quizzes, function($quiz) {
            return $quiz['user_id'] == $_SESSION['user_id'];
        });
        ?>
        <?php if (empty($user_quizzes)): ?>
            <p>У вас нет созданных квизов.</p>
        <?php else: ?>
            <table>
                <tr><th>Название</th><th>Действия</th></tr>
                <?php foreach ($user_quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_quiz" value="1">
                                <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    <a href="/quiz/create" class="btn">Создать новый квиз</a>
</div>
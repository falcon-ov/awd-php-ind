<div class="container">
    <h2>Управление пользователями</h2>
    
    <!-- Форма создания пользователя -->
    <h3>Добавить пользователя</h3>
    <form method="POST" action="/admin/users">
        <input type="hidden" name="create" value="1">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <label for="role">Роль:</label>
        <select id="role" name="role" required>
            <option value="user">Пользователь</option>
            <option value="admin">Администратор</option>
        </select>
        <button type="submit">Создать</button>
    </form>

    <!-- Таблица пользователей -->
    <h3>Список пользователей</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя пользователя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <form method="POST" action="/admin/users" style="display:inline;">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="error" onclick="return confirm('Удалить пользователя?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
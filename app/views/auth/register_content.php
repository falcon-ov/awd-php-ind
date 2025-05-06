<h2>Регистрация</h2>
<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<form id="registerForm" method="POST" action="/register">
    <label>Логин: <input type="text" name="username" required></label>
    <label>Email: <input type="email" name="email" required></label>
    <label>Пароль: <input type="password" name="password" required></label>
    <button type="submit">Зарегистрироваться</button>
</form>
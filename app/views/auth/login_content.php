<h2>Вход</h2>
<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<form id="loginForm" method="POST" action="/login" onsubmit="return validateForm('loginForm')">
    <label>Логин: <input type="text" name="username" required></label>
    <label>Пароль: <input type="password" name="password" required></label>
    <button type="submit">Войти</button>
</form>
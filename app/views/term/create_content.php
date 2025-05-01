<div class="container">
         <h2>Добавить термин</h2>
         <?php if (isset($_SESSION['message'])): ?>
             <div class="card" style="background: #dff0d8; color: #3c763d;">
                 <?php echo htmlspecialchars($_SESSION['message']); ?>
                 <?php unset($_SESSION['message']); ?>
             </div>
         <?php endif; ?>
         <?php if (isset($error)): ?>
             <p class="error"><?php echo htmlspecialchars($error); ?></p>
         <?php endif; ?>
         <form method="POST" action="/term/create">
             <label>Название: <input type="text" name="title" required></label>
             <label>Определение: <textarea name="definition" required></textarea></label>
             <label>Категория:
                 <select name="category" required>
                     <option value="IT">IT</option>
                     <option value="Маркетинг">Маркетинг</option>
                     <option value="Финансы">Финансы</option>
                 </select>
             </label>
             <label>Статус:
                 <input type="radio" name="status" value="active" checked> Активен
                 <input type="radio" name="status" value="inactive"> Неактивен
             </label>
             <button type="submit">Сохранить</button>
         </form>
     </div>
     
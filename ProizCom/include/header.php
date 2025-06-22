<?php
session_start();
?>
<header>
    <img src="images/logo.jpg" alt="Логотип Дом мастеров" id="logo">
    <h1>Дом мастеров!</h1>
    <nav>
        <a href="index.php">Главная</a>
        <a href="products.php">Продукция</a>
        <a href="contacts.php">Контакты</a>
        <a href="cart.php">Корзина</a>
    

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Если пользователь авторизован, показываем личный кабинет и выход -->
            <a href="account.php">Личный кабинет</a>
            <a href="logout.php">Выход</a>
        <?php else: ?>
            <!-- Если пользователь не авторизован, показываем ссылку на вход -->
            <a href="login.php">Вход</a>
        <?php endif; ?>
    </nav>
</header>

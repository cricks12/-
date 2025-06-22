<?php
session_start();

include ('connect.php');

if (isset($_SESSION['user_id'])) {
    header('Location: account_details.php'); // Если пользователь уже авторизован
    exit();
}

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'ProizComp');
    
    // Регистрация
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$password')";
        $conn->query($sql);
        header('Location: account.php');
    }
    
    // Вход
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM Users WHERE email = '$email'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: account_details.php');
        } else {
            $error = "Неверный логин или пароль!";
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Дом мастерок</title>
<link rel="stylesheet" href="style.css"> 
</head>
<body>
<header>
<img src="images/logo.jpg" alt="Логотип Дом мастерок" id="logo">
        <h1>Дом мастеров</h1>
        <nav>
            <a href="index.php">Главная</a>
            <a href="products.php">Продукция</a>
            <a href="contacts.php">Контакты</a>
            <a href="cart.php">Корзина</a>
            <a href="account.php">Личный кабинет</a>
        </nav>
    </header>

    <section>
        <h2>Регистрация</h2>
        <form action="account.php" method="POST">
            <label for="name">Имя:</label>
            <input type="text" name="name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Пароль:</label>
            <input type="password" name="password" required><br><br>

            <button type="submit" name="register">Зарегистрироваться</button>
        </form>

        <h2>Вход</h2>
        <form action="account.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Пароль:</label>
            <input type="password" name="password" required><br><br>

            <button type="submit" name="login">Войти</button>
        </form>
        
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </section>
    <?php include('include/footer.php'); ?>
</body>
</html>

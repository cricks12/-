<?php
session_start();

include('connect.php');
include('include/header.php');

if (isset($_SESSION['user_id'])) {
    header('Location: account_details.php'); // Если пользователь уже авторизован
    exit();
}

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'ProizComp');
    
    // Проверка на ошибки подключения к базе данных
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
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
            exit();
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
    <title>Вход - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section>
    <h2>Вход</h2>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Войти</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <p>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</section>
</body>
</html>

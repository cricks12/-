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
    
    // Регистрация
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Проверка на уникальность email
        $check_email_sql = "SELECT * FROM Users WHERE email = '$email'";
        $check_email_result = $conn->query($check_email_sql);
        
        if ($check_email_result->num_rows > 0) {
            $error = "Этот email уже используется!";
        } else {
            $sql = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                header('Location: login.php'); // После регистрации перенаправляем на страницу входа
                exit();
            } else {
                $error = "Ошибка регистрации. Попробуйте снова!";
            }
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
    <title>Регистрация - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section>
    <h2>Регистрация</h2>
    <form action="register.php" method="POST">
        <label for="name">Имя:</label>
        <input type="text" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="register">Зарегистрироваться</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</section>
</body>
</html>

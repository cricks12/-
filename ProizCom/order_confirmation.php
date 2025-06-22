<?php
session_start();
include('connect.php');
include('include/header.php');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Спасибо за заказ!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section>
    <h2>Спасибо за заказ!</h2>
    <p>Ваш заказ успешно оформлен и принят в обработку.</p>
    <a href="products.php">Вернуться в каталог</a>
</section>

</body>
</html>

<?php
$conn->close();
?>

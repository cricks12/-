<?php
session_start();
include('connect.php');
include('include/header.php');

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header('Location: account.php'); // Перенаправление на страницу авторизации
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение заказов пользователя
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История заказов - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <section>
        <h2>История заказов</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <li>
                        Заказ №<?php echo $order['id']; ?> - <?php echo $order['total_price']; ?> руб.
                        - Статус: <?php echo $order['status']; ?>
                        <br>Дата: <?php echo $order['order_date']; ?>
                        <br><a href="order_confirmation.php?order_id=<?php echo $order['id']; ?>">Посмотреть детали</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>У вас нет заказов.</p>
        <?php endif; ?>
    </section>

</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>

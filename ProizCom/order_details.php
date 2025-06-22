<?php
session_start();
include('connect.php');
include('include/header.php');

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header('Location: account.php'); // Перенаправление на страницу авторизации
    exit();
}

// Проверяем, что параметр order_id передан
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "Ошибка: заказ не найден.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение информации о заказе
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    
    // Получение товаров из заказанных товаров
    $sql_items = "SELECT oi.*, p.name FROM orders_items oi JOIN Products p ON oi.product_id = p.id WHERE oi.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param('i', $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
} else {
    echo "Заказ не найден.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали заказа - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <section>
        <h2>Детали заказа №<?php echo $order['id']; ?></h2>
        <p>Дата заказа: <?php echo $order['order_date']; ?></p>
        <p>Статус: <?php echo $order['status']; ?></p>
        <p>Итого: <?php echo $order['total_price']; ?> руб.</p>
        
        <h3>Товары:</h3>
        <?php if ($result_items->num_rows > 0): ?>
            <ul>
                <?php while ($item = $result_items->fetch_assoc()): ?>
                    <li>
                        <?php echo $item['name']; ?> - <?php echo $item['quantity']; ?> x <?php echo $item['price']; ?> руб.
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Товары не найдены для этого заказа.</p>
        <?php endif; ?>
    </section>

</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>

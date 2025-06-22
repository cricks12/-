<?php
session_start();
include('connect.php');
include('include/header.php');

// Проверка, что корзина не пуста
if (empty($_SESSION['cart'])) {
    header('Location: cart.php'); // Если корзина пуста, перенаправляем обратно в корзину
    exit();
}

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header('Location: account.php'); // Перенаправление на страницу авторизации
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart']));

// Если форма отправлена, создаем заказ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    $sql = "INSERT INTO orders (user_id, order_date, status, recipient_name, delivery_address, phone) VALUES (?, NOW(), 'pending', ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Ошибка подготовки запроса: ' . $conn->error);
    }

    $stmt->bind_param('isss', $user_id, $name, $address, $phone);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        foreach ($_SESSION['cart'] as $item) {
            $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_item = $conn->prepare($sql_item);

            if ($stmt_item === false) {
                die('Ошибка подготовки запроса для товаров: ' . $conn->error);
            }

            $stmt_item->bind_param('iiid', $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt_item->execute();
        }

        unset($_SESSION['cart']);
        header('Location: order_confirmation.php');
        exit();
    } else {
        echo "Ошибка при создании заказа. Попробуйте снова.";
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <section>
        <h2>Оформление заказа</h2>
        <form action="checkout.php" method="POST">
            <label for="name">Ваше имя:</label><br>
            <input type="text" name="name" id="name" required><br><br>

            <label for="address">Адрес доставки:</label><br>
            <input type="text" name="address" id="address" required><br><br>

            <label for="phone">Телефон:</label><br>
            <input type="tel" name="phone" id="phone" required><br><br>

            <h3>Итого: <?php echo number_format($total_price, 2, ',', ' '); ?> руб.</h3>


            <button type="submit">Подтвердить заказ</button>
        </form>
    </section>

</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>

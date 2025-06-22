<?php
session_start();
include ('connect.php');
include('include/header.php');

// Проверка на ошибки подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header('Location: account.php'); // Перенаправление на страницу авторизации
    exit();
}

// Если корзина пуста, создаем ее
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Добавление товара в корзину
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    // Получаем данные о товаре из БД
    $product_id = $_POST['product_id'];
    $sql = "SELECT * FROM Products WHERE id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    // Если товар найден, добавляем его в корзину
    if ($product) {
        $product_in_cart = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                $product_in_cart = true;
                break;
            }
        }

        if (!$product_in_cart) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }
    }
}

// Удаление товара из корзины
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Переиндексация массива
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина - Дом мастерок</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <section>
        <h2>Товары в корзине</h2>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Ваша корзина пуста.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <?php echo $item['name']; ?> - <?php echo $item['quantity']; ?> x <?php echo $item['price']; ?> руб.
                        <a href="cart.php?remove=<?php echo $item['id']; ?>">Удалить</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Итого: <?php echo array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])); ?> руб.</p>
            <a href="checkout.php">Оформить заказ</a> <!-- В этой ссылке можно будет добавить логику оформления -->
        <?php endif; ?>
    </section>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>

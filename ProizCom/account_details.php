<?php
session_start();
include('connect.php');
include('include/header.php');

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Перенаправление на страницу входа, если не авторизован
    exit();
}

// Получаем данные пользователя из базы данных
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM Users WHERE id = ?";
$stmt = $conn->prepare($sql);

// Проверяем, успешен ли запрос
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Если пользователь найден, выводим его данные
if ($user = $result->fetch_assoc()) {
    $name = $user['name'];
    $email = $user['email'];
} else {
    // Если пользователь не найден, перенаправляем на страницу ошибок
    header("Location: error.php");
    exit();
}

// Обработка формы обновления имени и email
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    // Обновляем данные пользователя в базе данных
    $update_sql = "UPDATE Users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);

    // Проверяем успешность подготовки запроса
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("ssi", $new_name, $new_email, $user_id);

    if ($stmt->execute()) {
        // Если обновление прошло успешно
        echo "<p>Данные успешно обновлены.</p>";
        // Обновляем информацию в текущей сессии
        $_SESSION['user_name'] = $new_name;
        $_SESSION['user_email'] = $new_email;
    } else {
        echo "<p>Ошибка при обновлении данных.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
<style>
    .order-history-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 24px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.order-history-btn:hover {
    background-color: #45a049;
}

</style>
</head>
<body>

<section>
    <h2>Данные пользователя</h2>
    <form action="account_details.php" method="POST">
        <label for="name">Имя пользователя:</label><br>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <button type="submit">Обновить данные</button>
    </form>

    <br>
    <a href="order_history.php" class="order-history-btn">📦 История заказов</a>
</section>


</body>
</html>
<?php include('include/footer.php'); ?>
<?php
// Закрытие соединения
$conn->close();
?>

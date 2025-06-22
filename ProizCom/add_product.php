<?php
include('connect.php');
include('include/header.php');

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];

    // Загрузка изображения
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $image_name = basename($_FILES['image']['name']);
        $target_path = $upload_dir . time() . '_' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }

    // Подготовленный запрос на добавление
    $sql = "INSERT INTO Products (name, description, price, image_path, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_path, $category_id);

    if ($stmt->execute()) {
        echo "<p>Товар успешно добавлен!</p>";
    } else {
        echo "<p>Ошибка: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
    <style>
        form {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #f9f9f9;
        }
        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Добавить товар</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="name">Название товара:</label>
    <input type="text" name="name" id="name" required>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" rows="4" required></textarea>

    <label for="price">Цена (₽):</label>
    <input type="number" name="price" id="price" step="0.01" required>

    <label for="category_id">Категория:</label>
    <select name="category_id" id="category_id" required>
        <option value="">-- выберите категорию --</option>
        <?php
        // Получаем категории из базы
        $sql = "SELECT id, name FROM Categories";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        ?>
    </select>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image" accept="image/*">

    <button type="submit">Добавить товар</button>
</form>

</body>
</html>

<?php
$conn->close();
?>

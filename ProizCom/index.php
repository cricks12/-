<?php
include('include/header.php');
include('connect.php'); // Подключаем к базе данных

// Получение товаров (например, последние 4 товара)
$sql_products = "SELECT * FROM Products ORDER BY id DESC LIMIT 3"; // Ограничение на 4 товара
$stmt_products = $conn->prepare($sql_products);
$stmt_products->execute();
$result_products = $stmt_products->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Стиль для мини-каталога */
        .mini-catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between; /* Разделяем карточки по ширине */
            margin-top: 30px;
        }

        .product-card {
            width: 23%; /* Размер каждой карточки */
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column; /* Размещаем элементы вертикально */
            align-items: center;
        }

        .product-card:hover {
            transform: translateY(-5px); /* Легкое поднятие карточки */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); /* Более сильная тень */
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        /* Увеличение изображения при наведении */
        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px; /* Отступ сверху */
            margin-bottom: 10px;
            color: #333; /* Цвет текста - темно-серый для лучшей видимости */
        }

        .product-card .product-price {
            font-size: 16px;
            color: #28a745; /* Зеленый цвет для цены */
            margin-bottom: 10px;
        }

        .add-to-cart-btn {
            padding: 12px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }

        /* Убедимся, что на мобильных устройствах карточки не выходят за пределы */
        @media (max-width: 768px) {
            .product-card {
                width: 48%; /* Два товара в ряд на мобильных устройствах */
            }
        }

        @media (max-width: 480px) {
            .product-card {
                width: 100%; /* Один товар в ряд на самых маленьких экранах */
            }
        }
    </style>
</head>
<body>

    <section>
        <h2>О нас</h2>
        <p>Мы – компания, которая предоставляет продукцию для дачи и огорода. Мы гордимся качеством и инновациями.</p>

        <div class="main-image">
            <img src="images/main-image.jpg" alt="Продукция Дом мастерок" class="responsive-img">
        </div>

        <p>Наши товары – это идеальное сочетание надежности и функциональности для вашего сада и огорода.</p>
    </section>

    <!-- Мини-каталог -->
    <section>
        <h2>Наши товары</h2>
        <div class="mini-catalog">
            <?php 
            if ($result_products->num_rows > 0) {
                while ($product = $result_products->fetch_assoc()): 
                    // Проверка существования изображения
                    $image_path = $product['image_path'];
                    if (!file_exists($image_path)) {
                        $image_path = 'default_image.jpg'; // Путь к изображению по умолчанию
                    }
            ?>
            <div class="product-card">
                <img src="<?php echo $image_path; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <h4 class="product-name"><?php echo $product['name']; ?></h4> <!-- Название под изображением -->
                <p class="product-price"><?php echo $product['price']; ?> руб.</p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="add-to-cart-btn">Добавить в корзину</button>
                </form>
            </div>
            <?php 
                endwhile;
            } else {
                echo '<p>Товары не найдены</p>';
            }
            ?>
        </div>
    </section>


    <?php include('include/footer.php'); ?>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>

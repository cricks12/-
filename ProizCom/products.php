<?php
include('connect.php');
include('include/header.php');

// Получение категорий
$stmt_categories = $conn->prepare("SELECT * FROM Categories");
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();

// Получение товаров с фильтрацией
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$sql_products = $category_id ? 
    "SELECT * FROM Products WHERE category_id = ?" : 
    "SELECT * FROM Products";
$stmt_products = $conn->prepare($sql_products);
if ($category_id) {
    $stmt_products->bind_param("i", $category_id);
}
$stmt_products->execute();
$result_products = $stmt_products->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Продукция — Дом мастерок</title>
    <link rel="stylesheet" href="style.css">
    <style>
.category-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}
.category-list a {
    padding: 12px 24px;
    background: #007BFF;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    border-radius: 6px;
    transition: 0.3s;
}
.category-list a:hover {
    background: #0056b3;
    transform: scale(1.05);
}

.products {
    display: grid;
     grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); /* 📌 карточка шире */
    align-items: start;
    justify-items: center;
}

.product-card {
    background: #fff;
    width: 350px;
    border: 1px solid #ddd;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.product-card:hover {
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}

.product-image {
    width: 100%;
    height: 260px;
    object-fit: contain;
    background: #f9f9f9;
    display: block;
}

.product-info {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 10px;
}
.product-description {
    font-size: 14px;
    color: #666;
    flex: 1;
    margin-bottom: 15px;
}
.product-price {
    font-size: 16px;
    font-weight: bold;
    color: #222;
    margin-bottom: 10px;
}

.add-to-cart-btn {
    padding: 12px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
}
.add-to-cart-btn:hover {
    background: #218838;
}

    </style>
</head>
<body>

<main>
    <section>
        <h2>Категории товаров</h2>
        <ul class="category-list">
            <?php if ($result_categories->num_rows): ?>
                <?php while ($category = $result_categories->fetch_assoc()): ?>
                    <li><a href="?category_id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>Категории не найдены</li>
            <?php endif; ?>
        </ul>

        <h3>Товары</h3>
        <div class="products">
            <?php if ($result_products->num_rows): ?>
                <?php while ($product = $result_products->fetch_assoc()): 
                    $image_path = file_exists($product['image_path']) ? $product['image_path'] : 'default_image.jpg';
                ?>
                    <div class="product-card">
                        <img src="<?= $image_path ?>" alt="<?= $product['name'] ?>" class="product-image">
                        <div class="product-info">
                            <h4 class="product-name"><?= $product['name'] ?></h4>
                            <p class="product-description"><?= $product['description'] ?></p>
                            <p class="product-price"><?= $product['price'] ?> руб.</p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="add-to-cart-btn">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Товары не найдены</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php include('include/footer.php'); ?>
</body>
</html>

<?php $conn->close(); ?>

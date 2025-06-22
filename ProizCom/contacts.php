<?php
include('include/header.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Дом мастерок</title>
<link rel="stylesheet" href="style.css"> 
</head>
<body>

    <section>
        <h2>Наши офисы</h2>
        <p>Адрес 1: ул. Ленина, д. 10, г. Москва, Россия</p>
        <p>Адрес 2: ул. Пушкина, д. 5, г. Санкт-Петербург, Россия</p>
        <p>Телефон: +7 (800) 123-45-67</p>
        <p>Email: info@dommasterok.ru</p>

        <h3>Контактная форма</h3>
        <form id="contactForm" action="send_contact.php" method="POST">
            <label for="name">Ваше имя:</label>
            <input type="text" name="name" id="name" required><br><br>

            <label for="email">Ваш email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="message">Сообщение:</label><br>
            <textarea name="message" id="message" rows="4" required></textarea><br><br>

            <button type="submit">Отправить</button>
        </form>
        <div id="formMessage" style="margin-top:15px;"></div>
        </div>
    </section>
    <script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault(); // отключаем стандартную отправку формы

    let form = this;
    let formData = new FormData(form);
    let messageBox = document.getElementById('formMessage');

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        messageBox.innerHTML = data; // выводим сообщение с сервера
        if (data.toLowerCase().includes('успешно')) {
            form.reset(); // очищаем форму, если успешно
        }
    })
    .catch(error => {
        messageBox.innerHTML = '<p style="color:red;">Ошибка при отправке. Попробуйте позже.</p>';
    });
});
</script>
<?php include('include/footer.php'); ?>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    $to = "info@dommasterok.ru";
    $subject = "Новое сообщение с сайта Дом Мастерок";
    $body = "Имя: $name\nEmail: $email\n\nСообщение:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo '<p style="color:green;">Спасибо! Ваше сообщение успешно отправлено.</p>';
    } else {
        echo '<p style="color:red;">Ошибка при отправке сообщения. Попробуйте снова.</p>';
    }
} else {
    echo '<p style="color:red;">Неверный запрос.</p>';
}
?>

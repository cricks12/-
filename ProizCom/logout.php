<?php
// Запускаем сессию
session_start();

// Удаляем все данные из сессии
session_unset();

// Уничтожаем саму сессию
session_destroy();

// Перенаправляем пользователя на главную страницу или на страницу входа
header("Location: index.php");
exit();
?>

<?php
session_start();
session_destroy(); // Завершуємо сесію
header("Location: login.php"); // Перенаправлення на сторінку входу
exit();
?>
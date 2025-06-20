<?php
$host = 'localhost';
$db = 'tareas';
$user = 'root';
$pass = '1804'; // або ваш пароль, якщо є

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}
?>

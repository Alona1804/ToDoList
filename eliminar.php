<?php
include 'config.php';
include 'header.php';
include 'footer.php';

// Отримання та перевірка параметра id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $conn->query("DELETE FROM tareas WHERE id = $id");
}

header("Location: index.php");
exit();

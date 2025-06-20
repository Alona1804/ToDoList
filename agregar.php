<?php
include 'config.php';
include 'header.php';
include 'footer.php';

// Перевірка отриманих даних
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$fecha = isset($_POST['fecha_limite']) ? $_POST['fecha_limite'] : NULL;
$code_de_tarea = isset($_POST['code_de_tarea']) ? $_POST['code_de_tarea'] : NULL;

if (!empty($titulo)) {
    $stmt = $conn->prepare("INSERT INTO tareas (titulo, descripcion, fecha_limite, code_de_tarea) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $code_de_tarea);
    $stmt->execute();
}

header("Location: index.php");
exit();
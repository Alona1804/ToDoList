<?php
include 'config.php';

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha_limite'];

$stmt = $conn->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, fecha_limite = ? WHERE id = ?");
$stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $id);
$stmt->execute();

header("Location: index.php");
exit();
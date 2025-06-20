<?php

include 'config.php';

$nombreTipo = $_POST['nombre_tipo'];


if (!empty($nombreTipo)) {
    $stmt = $conn->prepare("INSERT INTO tipo_tarea (tipo_de_tarea) VALUES (?)");
    $stmt->bind_param("s", $nombreTipo);
    $stmt->execute();
}

header('Location: index.php');
exit;
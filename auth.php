<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

function es_admin() {
    return isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] == "admin";
}
?>
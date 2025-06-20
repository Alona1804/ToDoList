<?php
// Отримати загальну кількість протермінованих завдань
$totalVencidas = $conn->query("
    SELECT COUNT(*) AS total FROM tareas 
    WHERE fecha_limite < CURDATE() AND completada = 0
")->fetch_assoc();

// Отримати кількість "нових" прострочених (за вчора)
$nuevasVencidas = $conn->query("
    SELECT COUNT(*) AS nuevos FROM tareas 
    WHERE fecha_limite = CURDATE() - INTERVAL 1 DAY AND completada = 0
")->fetch_assoc();
?>

<?php
include 'config.php';

// Отримати всі прострочені завдання
$tareasVencidas = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE fecha_limite < CURDATE() AND completada = 0
    ORDER BY fecha_limite ASC
")->fetch_all(MYSQLI_ASSOC);

// Отримати завдання, які прострочені вчора
$tareasNuevasVencidas = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE fecha_limite = CURDATE() - INTERVAL 1 DAY AND completada = 0
    ORDER BY fecha_limite ASC
")->fetch_all(MYSQLI_ASSOC);
?>



<header class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-vcentered is-flex">
                <!-- Логотип -->
                <div class="column is-3">
                    <img src="logo.png" alt="To-Do List" style="height: 100%">
                </div>

                <!-- Заголовок -->
                <div class="column is-6 has-text-centered">
                    <h1 class="title">📌 Administrador de Tareas</h1>
                    <h2 class="subtitle">¡Tic-tac, a trabajar! ⏳</h2>
                </div>

                <!-- Кнопки користувача -->
                <div class="column is-3 has-text-right">
                    <div class="is-flex is-justify-content-flex-end user-buttons-container">
                        <a class="button is-light">👤 <?= $_SESSION["usuario_nombre"] ?></a>
                        <a class="button is-danger" href="logout.php">🚪 Salir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Навігаційне меню -->
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarContenido">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarContenido" class="navbar-menu">
                <div class="navbar-start is-flex is-flex-wrap-wrap">
                    <a class="button is-light m-1" href="index.php">🏠 Inicio</a>
                    <a class="button is-primary m-1" href="index.php?form=true">➕ Agregar nueva tarea</a>
                    <a class="button is-link m-1" href="index.php?form_tipo_tarea=true">📝 Agregar nuevo tipo de tarea</a>
                    <a class="button is-danger m-1" href="index.php?filter=vencidas">⏳ Vencidas (<?= $totalVencidas['total'] ?>)</a>
                    <a class="button is-danger m-1" href="index.php?filter=vencidas_new">📆 Nuevas vencidas (<?= $nuevasVencidas['nuevos'] ?>)</a>
                </div>
            </div>
        </div>
    </nav>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const burger = document.querySelector('.navbar-burger');
        const target = document.getElementById(burger.dataset.target);

        burger.addEventListener('click', () => {
            burger.classList.toggle('is-active');
            target.classList.toggle('is-active');
        });
    });
    </script>
</header>
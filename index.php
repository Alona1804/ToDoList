<?php
include 'config.php';
include 'auth.php';
 ?>


<?php
// Отримуємо типи задач
$tiposTarea = $conn->query("SELECT * FROM tipo_tarea");

// Запити з JOIN, щоб отримати `tipo_de_tarea`
$tareasHoy = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE fecha_limite = CURDATE() 
    ORDER BY fecha_limite ASC
");

$tareasNoCompletadas = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE completada = 0 
    ORDER BY fecha_limite ASC
");

$tareasCompletadas = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE completada = 1 
    ORDER BY fecha_limite ASC
");

// Отримати всі завдання, які мають дедлайн завтра
$tareasPendientes = $conn->query("
    SELECT tareas.*, tipo_tarea.tipo_de_tarea 
    FROM tareas 
    LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
    WHERE fecha_limite = CURDATE() + INTERVAL 1 DAY 
    ORDER BY fecha_limite ASC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Tareas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
   <?php include 'header.php';?>

<div class="container">
    <div class="columns">
        <!-- Menú lateral -->
      
        <aside class="column is-3">
            <h1 class="title has-text-centered">📂 Filtros de Tareas</h1>
            <div class="box">
                <a class="button is-fullwidth" href="index.php">📋 Todas las tareas</a>
                <a class="button is-fullwidth" href="?filter=no_completadas">🚧 No completadas</a>
                <a class="button is-fullwidth" href="?filter=completadas">✅ Completadas</a>
                <a class="button is-fullwidth" href="?filter=hoy">📅 Hoy</a>
                <a class="button is-fullwidth" href="?filter=pendiente">🕰️ Pendiente</a>
                <a class="button is-fullwidth is-info" href="?filter=ordenar">📑 Ordenar tareas</a>
            </div>
        </aside>

        <!-- Panel de contenido -->

         <?php include 'form.php'; ?>
          <?php include 'form_tipo_tarea.php'; ?>


        <?php if (!isset($_GET['form']) && !isset($_GET['form_tipo_tarea'])): ?>
        
        <div class="column is-9">
      <?php if (isset($_GET['filter']) && $_GET['filter']=='ordenar'): ?>
<div class="box has-background-light">
    <h2 class="subtitle">📑 Lista de todas las tareas</h2>

    <!-- Форма вибору сортування -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="filter" value="ordenar"> <!-- Додано! -->
        <label class="label">Ordenar por:</label>
        <div class="select">
            <select name="sort_by">
                <option value="fecha_limite" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'fecha_limite' ? 'selected' : '' ?>>Fecha límite</option>
                <option value="completada" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'completada' ? 'selected' : '' ?>>Completadas primero</option>
                <option value="tipo_de_tarea" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'tipo_de_tarea' ? 'selected' : '' ?>>Tipo de tarea</option>
                <option value="titulo" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'titulo' ? 'selected' : '' ?>>Nombre</option>
            </select>
        </div>
        <button type="submit" class="button is-primary mt-2">Ordenar</button>
    </form>

    <?php
    // Вибір сортування
    $ordenarPor = isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['fecha_limite', 'completada', 'tipo_de_tarea', 'titulo']) 
        ? $_GET['sort_by'] 
        : 'fecha_limite';

    $tareasOrdenadas = $conn->query("
        SELECT tareas.*, tipo_tarea.tipo_de_tarea 
        FROM tareas 
        LEFT JOIN tipo_tarea ON tareas.code_de_tarea = tipo_tarea.code_de_tarea
        ORDER BY $ordenarPor ASC
    ")->fetch_all(MYSQLI_ASSOC);
    ?>

    <?php foreach ($tareasOrdenadas as $row): ?>
    <article class="message is-info">
        <div class="message-header">
            <p>📑 <?= htmlspecialchars($row['titulo']) ?> (<?= htmlspecialchars($row['tipo_de_tarea']) ?>) - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
            <div>
                <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                <?php if (es_admin()): ?> 
                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="message-body">
            <?= htmlspecialchars($row['descripcion']) ?>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
            <div class="columns is-multiline">
            


               <!-- Tareas pendientes -->
                <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'pendiente'): ?>
                    <div class="column is-half">
                        <div class="box has-background-danger-light">
                            <!-- Заголовок для згортання -->
                            <h2 class="subtitle" onclick="toggleBox('pendientes')" style="cursor: pointer;">
                                🕰️ Tareas pendientes (hasta mañana)
                                <span id="icon-pendientes" style="float: right;">➕</span>
                            </h2>

                            <!-- Контейнер зі списком -->
                            <div id="pendientes" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php foreach ($tareasPendientes as $row): ?>
                                <article class="message is-link">
                                    <div class="message-header">
                                        <p>🕰️ <?= htmlspecialchars($row['titulo']) ?> - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botones">
                                            <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                                            <?php if (es_admin()): ?>
                                                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                                                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <!-- Tareas para hoy -->
                <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'hoy'): ?>
                    <div class="column is-half">
                        <div class="box has-background-rose-lavender">
                            <h2 class="subtitle" onclick="toggleBox('hoy')" style="cursor: pointer;">
                                📅 Tareas para hoy
                                <span id="icon-hoy" style="float: right;">➕</span>
                            </h2>

                            <div id="hoy" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php while ($row = $tareasHoy->fetch_assoc()): ?>
                                <article class="message is-info">
                                    <div class="message-header">
                                        <p><?= htmlspecialchars($row['titulo']) ?> 
                                            (<?= !empty($row['tipo_de_tarea']) ? htmlspecialchars($row['tipo_de_tarea']) : 'Sin tipo' ?>) 
                                            – hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botones">
                                            <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                                            <?php if (es_admin()): ?> 
                                                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                                                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <!-- Tareas no completadas -->
                 <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'no_completadas'): ?>
                    <div class="column is-half">
                        <div class="box has-background-warning-light">
                            <h2 class="subtitle" onclick="toggleBox('noCompletadas')" style="cursor: pointer;">
                                🚧 Tareas no completadas
                                <span id="icon-noCompletadas" style="float: right;">➕</span>
                            </h2>

                            <div id="noCompletadas" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php while ($row = $tareasNoCompletadas->fetch_assoc()): ?>
                                <article class="message is-warning">
                                    <div class="message-header">
                                        <p><?= htmlspecialchars($row['titulo']) ?> 
                                            (<?= !empty($row['tipo_de_tarea']) ? htmlspecialchars($row['tipo_de_tarea']) : 'Sin tipo' ?>) 
                                            - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botones">
                                            <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                                            <?php if (es_admin()): ?> 
                                                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                                                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <!-- Tareas completadas -->
                <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'completadas'): ?>
                    <div class="column is-half">
                        <div class="box has-background-success-light">
                            <h2 class="subtitle" onclick="toggleBox('completadas')" style="cursor: pointer;">
                                ✅ Tareas completadas
                                <span id="icon-completadas" style="float: right;">➕</span>
                            </h2>

                            <div id="completadas" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php while ($row = $tareasCompletadas->fetch_assoc()): ?>
                                <article class="message is-success">
                                    <div class="message-header">
                                        <p><?= htmlspecialchars($row['titulo']) ?> 
                                            (<?= !empty($row['tipo_de_tarea']) ? htmlspecialchars($row['tipo_de_tarea']) : 'Sin tipo' ?>) 
                                            - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botonescompletadas">
                                            <a class="button is-small is-warning" href="desmarcar.php?id=<?= $row['id'] ?>">🔄</a>
                                            <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Estás seguro?')">🗑️</a>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                

                <!-- Tareas vencidas -->
                <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'vencidas'): ?>
                    <div class="column is-half">
                        <div class="box has-background-dark-light">
                            <h2 class="subtitle" onclick="toggleBox('vencidas')" style="cursor: pointer;">
                                ❌ Todas las vencidas
                                <span id="icon-vencidas" style="float: right;">➕</span>
                            </h2>

                            <div id="vencidas" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php foreach ($tareasVencidas as $row): ?>
                                <article class="message is-dark">
                                    <div class="message-header">
                                        <p><?= htmlspecialchars($row['titulo']) ?> - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botones">
                                            <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                                            <?php if (es_admin()): ?>
                                                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                                                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <!-- Tareas vencidas ayer -->
                <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'vencidas_new'): ?>
                    <div class="column is-half">
                        <div class="box has-background-grey-lighter">
                            <h2 class="subtitle" onclick="toggleBox('vencidasAyer')" style="cursor: pointer;">
                                📉 Vencidas ayer
                                <span id="icon-vencidasAyer" style="float: right;">➕</span>
                            </h2>

                            <div id="vencidasAyer" <?php if (!isset($_GET['filter'])): ?>style="display: none;"<?php endif;?>>
                                <?php foreach ($tareasNuevasVencidas as $row): ?>
                                <article class="message is-warning">
                                    <div class="message-header">
                                        <p><?= htmlspecialchars($row['titulo']) ?> - hasta <?= htmlspecialchars($row['fecha_limite']) ?></p>
                                        <div class="botones">
                                            <a class="button is-small is-success" href="completar.php?id=<?= $row['id'] ?>">✅</a>
                                            <?php if (es_admin()): ?> 
                                                <a class="button is-small is-info" href="editar.php?id=<?= $row['id'] ?>">✏️</a>
                                                <a class="button is-small is-danger" href="eliminar.php?id=<?= $row['id'] ?>">🗑️</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>




            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>

<script>
function toggleBox(id) {
    const box = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (box.style.display === 'none') {
        box.style.display = 'block';
        if (icon) icon.textContent = '➖';
    } else {
        box.style.display = 'none';
        if (icon) icon.textContent = '➕';
    }
}
</script>
</body>
</html>
<?php
include 'config.php';
include 'auth.php';




// Отримання ID задачі
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Отримання даних задачі
$result = $conn->query("SELECT * FROM tareas WHERE id = $id");
$tarea = $result->fetch_assoc();

// Отримання списку типів задачі
$tiposTarea = $conn->query("SELECT * FROM tipo_tarea");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body class="section">
<?php include 'header.php';?>
<div class="container">
    <h1 class="title has-text-centered">✏️ Editar Tarea</h1>
    <form action="actualizar.php" method="post" class="box">
        <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
        <div class="field">
            <label class="label">Título</label>
            <div class="control">
                <input class="input" type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Descripción</label>
            <div class="control">
                <textarea class="textarea" name="descripcion"><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
            </div>
        </div>
        <div class="field">
            <label class="label">Fecha límite</label>
            <div class="control">
                <input class="input" type="date" name="fecha_limite" value="<?= $tarea['fecha_limite'] ?>">
            </div>
        </div>
        <div class="field">
            <label class="label">Tipo de tarea</label>
            <div class="control">
                <div class="select">
                    <select name="code_de_tarea">
                        <?php while ($tipo = $tiposTarea->fetch_assoc()): ?>
                            <option value="<?= $tipo['code_de_tarea'] ?>" <?= $tipo['code_de_tarea'] == $tarea['code_de_tarea'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['tipo_de_tarea']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="control">
            <button class="button is-success">Actualizar tarea</button>
        </div>
    </form>
</div>
<?php include 'footer.php';?>
</body>
</html>
<?php
if (isset($_GET['form_tipo_tarea'])) {
    ?>
    <form method="POST" action="guardar_tipo_tarea.php" class="box" style="margin-top: 20px;">
        <div class="field">
            <label class="label">Nombre del tipo de tarea</label>
            <div class="control">
                <input class="input" type="text" name="nombre_tipo" required>
            </div>
        </div>

        <div class="field is-grouped">
            <div class="control">
                <button class="button is-success" type="submit">Guardar</button>
            </div>
            <div class="control">
                <a class="button is-light" href="index.php">Cancelar</a>
            </div>
        </div>
    </form>
    <?php
}
?>
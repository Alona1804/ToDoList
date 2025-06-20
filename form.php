<?php if (isset($_GET['form'])): ?>
<div class="column is-half">
    <div class="box">
        <h2 class="subtitle">➕ Agregar nueva tarea</h2>
        <form action="agregar.php" method="post">
            <div class="field">
                <label class="label">Título</label>
                <div class="control">
                    <input class="input" type="text" name="titulo" required placeholder="Ejemplo: Comprar productos">
                </div>
            </div>
            <div class="field">
                <label class="label">Descripción</label>
                <div class="control">
                    <textarea class="textarea" name="descripcion" placeholder="Detalles de la tarea..."></textarea>
                </div>
            </div>
            <div class="field">
                <label class="label">Fecha límite</label>
                <div class="control">
                    <input class="input" type="date" name="fecha_limite">
                </div>
            </div>
            <div class="field">
                <label class="label">Tipo de tarea</label>
                <div class="control">
                    <div class="select">
                        <select name="code_de_tarea">
                            <?php while ($tipo = $tiposTarea->fetch_assoc()): ?>
                                <option value="<?= $tipo['code_de_tarea'] ?>"><?= htmlspecialchars($tipo['tipo_de_tarea']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="control">
                <button class="button is-primary">Agregar tarea</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
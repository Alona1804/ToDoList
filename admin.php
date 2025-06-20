
<?php include 'auth.php'; ?>
<?php if (!es_admin()): ?>
    <p class="error">🚫 No tienes acceso a esta sección.</p>
    <?php exit(); ?>
<?php endif; ?>

<h1>👑 Panel de Administración</h1>
<p>Bienvenido, administrador. Aquí puedes gestionar usuarios y tareas.</p>

<!-- Додайте функціонал адміністрування тут -->
 
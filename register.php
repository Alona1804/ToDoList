<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol']; // 'admin' або 'usuario'

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $password, $rol);
    $stmt->execute();

    echo "Usuario creado con éxito.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Центрування форми */
.register-form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
}

/* Стилізація форми */
.register-form {
    width: 450px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
    background: white;
}

/* Поля введення */
.input {
    border: 2px solid #3273dc;
    border-radius: 6px;
}

/* Кнопка */
.button {
    font-size: 1.2rem;
    padding: 10px;
    border-radius: 6px;
}

/* Заголовки */
.label {
    font-weight: bold;
    color: #363636;
}

/* Посилання */
a {
    color: #ff3860;
    font-weight: bold;
    text-decoration: none;
}
    </style>
</head>
<body>
    <div class="register-form-container">
        <form method="POST" class="register-form box">
            <div class="field">
                <label class="label">Nombre:</label>
                <div class="control">
                    <input class="input is-success" type="text" name="nombre" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Email:</label>
                <div class="control">
                    <input class="input is-info" type="email" name="email" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Contraseña:</label>
                <div class="control">
                    <input class="input is-warning" type="password" name="password" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Rol:</label>
                <div class="control">
                    <div class="select is-primary">
                        <select name="rol">
                            <option value="usuario">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary is-fullwidth">Registrar</button>
                </div>
            </div>

            <p class="has-text-centered">
                ¿Ya tienes cuenta? <a href="login.php">Login</a>.
            </p>
        </form>
    </div>
</body>
</html>
<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $hashed_password, $rol);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["usuario_id"] = $id;
            $_SESSION["usuario_nombre"] = $nombre;
            $_SESSION["usuario_rol"] = $rol;
            header("Location: index.php");
            exit();
        } else {
            echo "<p class='error'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p class='error'>Usuario no encontrado.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Центрування форми */
        .login-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        /* Стилізація форми */
        .login-form {
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

        /* Кнопка входу */
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
    <div class="login-form-container">
        <form method="POST" class="login-form box">
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
                <div class="control">
                    <button class="button is-primary is-fullwidth">Iniciar sesión</button>
                </div>
            </div>

            <p class="has-text-centered">
                ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.
            </p>
        </form>
    </div>
</body>
</html>
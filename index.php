<?php

session_start();

require_once "connet.php";

// Si ya inició sesión, ir directamente a home
if (isset($_SESSION["usuario"])) {
    header("Location: home.php");
    exit();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"] ?? "";
    $password = $_POST["password"] ?? "";

    if (!empty($usuario) && !empty($password)) {
        $sql = "SELECT id_usuario, usuario, contraseña, nombre, apellido
                FROM Usuarios
                WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 1) {
            $user = $resultado->fetch_assoc();
            // Para la contraseña actual: 123456
            if ($password === $user["contraseña"]) {
                $_SESSION["id_usuario"] = $user["id_usuario"];
                $_SESSION["usuario"] = $user["usuario"];
                $_SESSION["nombre"] = $user["nombre"];
                $_SESSION["apellido"] = $user["apellido"];
                header("Location: home.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
        $stmt->close();
    } else {
        $error = "Complete todos los campos.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - PA3</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Iniciar Sesión</h1>
            <p>User:Admin Password:123456</p>
            <?php if (isset($error)): ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario"
                        required>

                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña"
                        required>
                </div>

                <button type="submit">
                    Ingresar
                </button>

            </form>
        </div>
    </div>
</body>
</html>
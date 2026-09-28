<?php
session_start();
// Cerrar sesión
if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}

// Verificar sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . "/connet.php";

$tablas = [
    "Usuarios" => "Usuarios",
    "PacienteActuales" => "Paciente Actuales",
    "PacienteSalida" => "Pacientes Salida",
    "Ganancia" => "Ganancia"
];

$tablaSeleccionada = $_GET["tabla"] ?? "Usuarios";

if (!array_key_exists($tablaSeleccionada, $tablas)) {
    $tablaSeleccionada = "Usuarios";
}

$sql = "SELECT * FROM `$tablaSeleccionada`";
$resultado = $conn->query($sql);
$datos = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="salir">

        <a href="home.php?salir=1" class="btn-salir">
            Salir al Login
        </a>

    </div>

    <div class="tabs">
        <?php foreach ($tablas as $nombre => $titulo): ?>
            <a
                href="home.php?tabla=<?php echo urlencode($nombre); ?>"
                class="tab <?php echo ($tablaSeleccionada === $nombre) ? 'active' : ''; ?>"
            >
                <?php echo htmlspecialchars($titulo); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="content">
        <h2>
            <?php echo htmlspecialchars($tablas[$tablaSeleccionada]); ?>
        </h2>
        <?php if (!empty($datos)): ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($datos[0]) as $columna): ?>
                            <th>
                                <?php echo htmlspecialchars($columna); ?>
                            </th>

                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($datos as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $valor): ?>
                                <td>
                                    <?php echo htmlspecialchars($valor ?? ''); ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php else: ?>
            <div class="sin-datos">
                No hay datos registrados.
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php
$conn->close();
?>
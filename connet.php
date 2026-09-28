<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "datauser";
$puerto = 3306;


$conn = new mysqli(
    $servidor, $usuario, $password, $base_datos, $puerto
);

if ($conn->connect_error) {
    die("Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
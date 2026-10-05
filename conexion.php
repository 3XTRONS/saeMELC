<?php
$host = "localhost";
$usuario = "root";
$contrasenia = "";
$base_de_datos = "saeMELC";

$conexion = new mysqli($host, $usuario, $contrasenia, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>

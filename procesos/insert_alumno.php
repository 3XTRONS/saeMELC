<?php
// Incluir el archivo de conexión
include_once("../conexion.php");

// Procesar los datos enviados mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre           = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $numero_control   = $_POST['numero_control'] ?? '';
    $correo           = $_POST['correo'] ?? '';

    // Consulta SQL con la estructura correcta de la tabla 'alumnos'
    $sql = "INSERT INTO alumnos (matricula_al, nombre_al, apaterno_al, amaterno_al, mail_al, estatus_al) VALUES (?, ?, ?, ?, ?, 'ALTA')";
    
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en la consulta SQL: " . $conexion->error);
    }

    $stmt->bind_param("sssss", $numero_control, $nombre, $apellido_paterno, $apellido_materno, $correo);

    if ($stmt->execute()) {
        header("Location: ../registro/reg_alumno.php?status=success");
        exit();
    } else {
        header("Location: ../registro/reg_alumno.php?status=error&msg=" . urlencode($conexion->error));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../registro/reg_alumno.php");
    exit();
}
?>

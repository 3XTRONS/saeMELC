<?php
include_once("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idprof           = intval($_POST['idprof']);
    $nombre           = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo           = $_POST['correo'] ?? '';

    // Insertamos directamente el idprof digitado por el usuario
    $sql = "INSERT INTO profesores (idprof, nombre_prof, apaterno_prof, amaterno_prof, mail_prof, estatus_prof) VALUES (?, ?, ?, ?, ?, 'ALTA')";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error SQL: " . $conexion->error);
    }

    $stmt->bind_param("issss", $idprof, $nombre, $apellido_paterno, $apellido_materno, $correo);

    if ($stmt->execute()) {
        header("Location: ../registro/reg_profesor.php?status=success");
        exit();
    } else {
        header("Location: ../registro/reg_profesor.php?status=error&msg=" . urlencode($conexion->error));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../registro/reg_profesor.php");
    exit();
}
?>

<?php
include_once("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave_grupo  = $_POST['clave_grupo'] ?? '';
    $nombre_grupo = $_POST['nombre_grupo'] ?? '';

    $sql = "INSERT INTO grupos (clave_grupo, nombre_grupo, estatus_grupo) VALUES (?, ?, 'ALTA')";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error SQL: " . $conexion->error);
    }

    $stmt->bind_param("ss", $clave_grupo, $nombre_grupo);

    if ($stmt->execute()) {
        header("Location: ../registro/reg_grupo.php?status=success");
        exit();
    } else {
        header("Location: ../registro/reg_grupo.php?status=error&msg=" . urlencode($conexion->error));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../registro/reg_grupo.php");
    exit();
}
?>

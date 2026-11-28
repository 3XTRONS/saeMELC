<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $conexion->prepare("UPDATE materias SET ESTATUS = 'BAJA' WHERE ID_MATERIAS = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: " . $base . "/crude/crude_materias.php");
exit;
?>

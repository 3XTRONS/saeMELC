<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $conexion->prepare("UPDATE unidad SET ESTATUS = 'BAJA' WHERE ID_UNIDAD = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: " . $base . "/crude/crude_unidades.php");
exit;
?>

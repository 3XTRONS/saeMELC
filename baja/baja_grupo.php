<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $conexion->prepare("UPDATE grupos SET ESTATUS = 'BAJA' WHERE ID_GRUPOS = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: " . $base . "/crude/crude_grupos.php");
exit;
?>

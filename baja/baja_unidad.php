<?php
include_once("../conexion.php");

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conexion->prepare("UPDATE unidades SET estatus_unidad = 'BAJA' WHERE id_unidad = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../crude/crude_unidades.php");
exit();
?>

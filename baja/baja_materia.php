<?php
include_once("../conexion.php");

if (isset($_GET['idmat'])) {
    $idmat = intval($_GET['idmat']);

    $sql = "UPDATE materias SET estatus_mat = 'BAJA' WHERE idmat = ?";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idmat);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_materias.php?status=baja_ok");
            exit();
        } else {
            echo "Error al dar de baja: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
} else {
    header("Location: ../crude/crude_materias.php");
    exit();
}
$conexion->close();
?>

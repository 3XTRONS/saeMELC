<?php
include_once("../conexion.php");

if (isset($_GET['idprof'])) {
    $idprof = intval($_GET['idprof']);

    $sql = "UPDATE profesores SET estatus_prof = 'BAJA' WHERE idprof = ?";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idprof);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_profesores.php?status=baja_ok");
            exit();
        } else {
            echo "Error al dar de baja: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
} else {
    header("Location: ../crude/crude_profesores.php");
    exit();
}
$conexion->close();
?>

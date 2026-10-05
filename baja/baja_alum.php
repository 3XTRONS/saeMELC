<?php
include_once("../conexion.php");

if (isset($_GET['idalumn'])) {
    $idalumn = intval($_GET['idalumn']);

    // Actualización de estatus a BAJA
    $sql = "UPDATE alumnos SET estatus_al = 'BAJA' WHERE idalumn = ?";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idalumn);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_alumno.php?status=baja_ok");
            exit();
        } else {
            echo "Error al dar de baja: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
} else {
    header("Location: ../crude/crude_alumno.php");
    exit();
}
$conexion->close();
?>

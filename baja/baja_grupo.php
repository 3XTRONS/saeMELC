<?php
include_once("../conexion.php");

if (isset($_GET['idgrupo'])) {
    $idgrupo = intval($_GET['idgrupo']);

    $sql = "UPDATE grupos SET estatus_grupo = 'BAJA' WHERE idgrupo = ?";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idgrupo);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_grupos.php?status=baja_ok");
            exit();
        } else {
            echo "Error al dar de baja: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
} else {
    header("Location: ../crude/crude_grupos.php");
    exit();
}
$conexion->close();
?>

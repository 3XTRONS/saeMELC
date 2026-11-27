<?php
include_once("../conexion.php");

$id = 0;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (isset($_GET['idgrupo'])) {
    $id = (int)$_GET['idgrupo'];
}

if ($id > 0) {
    $sql = "UPDATE grupos SET estatus_grupo = 'BAJA' WHERE idgrupo = $id";
    $conexion->query($sql);
}

header("Location: ../crude/crude_grupos.php");
exit;
?>

<?php
include_once("../conexion.php");

$mensaje = "";
$grupo = null;

if (isset($_GET['idgrupo'])) {
    $idgrupo = intval($_GET['idgrupo']);
    $sql = "SELECT * FROM grupos WHERE idgrupo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idgrupo);
    $stmt->execute();
    $res = $stmt->get_result();
    $grupo = $res->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idgrupo      = intval($_POST['idgrupo']);
    $clave_grupo  = $_POST['clave_grupo'] ?? '';
    $nombre_grupo = $_POST['nombre_grupo'] ?? '';

    $sql = "UPDATE grupos SET clave_grupo = ?, nombre_grupo = ? WHERE idgrupo = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssi", $clave_grupo, $nombre_grupo, $idgrupo);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_grupos.php?status=update_ok");
            exit();
        } else {
            $mensaje = '<div class="alert alert-danger mt-3">Error al actualizar: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Grupo - SAE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 1000px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .titulo h2 { margin: 0; font-size: 1.8rem; }
        .contenido { padding: 25px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-sae:hover { background-color: #881337; color: white; }
    </style>
</head>
<body>

<?php include_once("../navegacion/nav.php"); ?>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2>Modificar Grupo</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <?php if ($grupo): ?>
            <form method="post" action="modif_grupo.php">
                <input type="hidden" name="idgrupo" value="<?php echo $grupo['idgrupo']; ?>">

                <div class="mb-3">
                    <label class="form-label">Clave del grupo</label>
                    <input type="text" name="clave_grupo" class="form-control" value="<?php echo htmlspecialchars($grupo['clave_grupo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre del grupo / Semestre</label>
                    <input type="text" name="nombre_grupo" class="form-control" value="<?php echo htmlspecialchars($grupo['nombre_grupo']); ?>" required>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-sae">Guardar Cambios</button>
                    <a href="../crude/crude_grupos.php" class="btn btn-outline-secondary">Cancelar y regresar al catálogo</a>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-warning">No se encontró la información del grupo.</div>
                <a href="../crude/crude_grupos.php" class="btn btn-secondary">Regresar al catálogo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

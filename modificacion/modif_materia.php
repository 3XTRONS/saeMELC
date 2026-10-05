<?php
include_once("../conexion.php");

$mensaje = "";
$materia = null;

if (isset($_GET['idmat'])) {
    $idmat = intval($_GET['idmat']);
    $sql = "SELECT * FROM materias WHERE idmat = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idmat);
    $stmt->execute();
    $res = $stmt->get_result();
    $materia = $res->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idmat      = intval($_POST['idmat']);
    $clave_mat  = $_POST['clave_mat'] ?? '';
    $nombre_mat = $_POST['nombre_mat'] ?? '';
    $creditos   = intval($_POST['creditos'] ?? 0);

    $sql = "UPDATE materias SET clave_mat = ?, nombre_mat = ?, creditos = ? WHERE idmat = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssii", $clave_mat, $nombre_mat, $creditos, $idmat);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_materias.php?status=update_ok");
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
    <title>Modificar Materia - SAE</title>
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
            <h2>Modificar Materia</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <?php if ($materia): ?>
            <form method="post" action="modif_materia.php">
                <input type="hidden" name="idmat" value="<?php echo $materia['idmat']; ?>">

                <div class="mb-3">
                    <label class="form-label">Clave de la materia</label>
                    <input type="text" name="clave_mat" class="form-control" value="<?php echo htmlspecialchars($materia['clave_mat']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre de la materia</label>
                    <input type="text" name="nombre_mat" class="form-control" value="<?php echo htmlspecialchars($materia['nombre_mat']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Créditos</label>
                    <input type="number" name="creditos" class="form-control" value="<?php echo htmlspecialchars($materia['creditos']); ?>" required>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-sae">Guardar Cambios</button>
                    <a href="../crude/crude_materias.php" class="btn btn-outline-secondary">Cancelar y regresar al catálogo</a>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-warning">No se encontró la información de la materia.</div>
                <a href="../crude/crude_materias.php" class="btn btn-secondary">Regresar al catálogo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

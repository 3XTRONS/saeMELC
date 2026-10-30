<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";
$id_unidad = intval($_GET['id'] ?? 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_unidad     = intval($_POST['id_unidad'] ?? 0);
    $nombre_unidad = trim($_POST['nombre_unidad'] ?? '');

    if ($id_unidad > 0 && !empty($nombre_unidad)) {
        $stmt = $conexion->prepare("UPDATE unidades SET nombre_unidad = ? WHERE id_unidad = ?");
        if ($stmt) {
            $stmt->bind_param("si", $nombre_unidad, $id_unidad);
            if ($stmt->execute()) {
                header("Location: ../crude/crude_unidades.php");
                exit();
            } else {
                $mensaje = "<div class='alert alert-danger mt-3'>Error al actualizar: " . htmlspecialchars($stmt->error) . "</div>";
            }
            $stmt->close();
        }
    }
}

$unidad = null;
if ($id_unidad > 0) {
    $res = $conexion->query("SELECT * FROM unidades WHERE id_unidad = $id_unidad");
    if ($res && $res->num_rows > 0) {
        $unidad = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Unidad</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 800px;
            margin: 30px auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .titulo {
            background-color: #172554;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .titulo h2 { margin: 0; font-size: 1.8rem; font-weight: 500; }
        .contenido { padding: 25px; }
        .btn-sae {
            background-color: #9f1239;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .btn-sae:hover { background-color: #881337; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2>Modificar Unidad</h2>
        </div>
        
        <div class="contenido">
            <?php echo $mensaje; ?>

            <?php if ($unidad): ?>
                <form method="POST" action="modif_unidad.php?id=<?php echo $id_unidad; ?>">
                    <input type="hidden" name="id_unidad" value="<?php echo $unidad['id_unidad']; ?>">

                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Nombre de la Unidad</label>
                        <input type="text" name="nombre_unidad" class="form-control" value="<?php echo htmlspecialchars($unidad['nombre_unidad']); ?>" required>
                    </div>

                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-sae mr-2">Guardar Cambios</button>
                        <a href="../crude/crude_unidades.php" class="btn btn-outline-secondary">Cancelar y regresar al catálogo</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Unidad no encontrada.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

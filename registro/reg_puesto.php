<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_puesto = trim($_POST['nombre_puesto'] ?? '');
    if (!empty($nombre_puesto)) {
        $stmt = $conexion->prepare("INSERT INTO puestos (nombre_puesto, estatus_puesto) VALUES (?, 'ALTA')");
        $stmt->bind_param("s", $nombre_puesto);
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>¡Puesto registrado correctamente!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Puestos</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 800px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 30px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="formulario">
        <div class="titulo"><h2>Registro de Puestos</h2></div>
        <div class="contenido">
            <?php echo $mensaje; ?>
            <form method="POST" action="reg_puesto.php">
                <div class="mb-4">
                    <label class="form-label font-weight-bold">Nombre del Puesto</label>
                    <input type="text" name="nombre_puesto" class="form-control" placeholder="Ej. Subdirector Académico" required>
                </div>
                <button type="submit" class="btn btn-sae mr-2">Guardar Puesto</button>
                <a href="../crude/crude_puestos.php" class="btn btn-outline-secondary">Ver Catálogo</a>
            </form>
        </div>
        <div class="pie">Registro de puestos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

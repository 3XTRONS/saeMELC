<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_unidad = trim($_POST['nombre_unidad'] ?? '');

    if (!empty($nombre_unidad)) {
        $stmt = $conexion->prepare("INSERT INTO unidades (nombre_unidad, estatus_unidad) VALUES (?, 'ALTA')");
        if ($stmt) {
            $stmt->bind_param("s", $nombre_unidad);
            if ($stmt->execute()) {
                $mensaje = "<div class='alert alert-success mt-3'>¡Unidad registrada correctamente!</div>";
            } else {
                $mensaje = "<div class='alert alert-danger mt-3'>Error al registrar: " . htmlspecialchars($stmt->error) . "</div>";
            }
            $stmt->close();
        }
    } else {
        $mensaje = "<div class='alert alert-warning mt-3'>Por favor escribe el nombre de la unidad.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Unidades</title>
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
        .contenido { padding: 30px; }
        .btn-sae {
            background-color: #9f1239;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie {
            text-align: center;
            padding: 15px;
            color: #666;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2>Registro de Unidades</h2>
        </div>
        
        <div class="contenido">
            <?php echo $mensaje; ?>

            <form method="POST" action="reg_unidad.php">
                <div class="mb-4">
                    <label class="form-label font-weight-bold">Nombre de la Unidad</label>
                    <input type="text" name="nombre_unidad" class="form-control" placeholder="Ej. Unidad 1 - Parcial 1" required>
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-sae mr-2">Registrar unidad</button>
                    <a href="../crude/crude_unidades.php" class="btn btn-outline-secondary">Ver catálogo de unidades</a>
                </div>
            </form>
        </div>

        <div class="pie">
            Registro de unidades - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

</body>
</html>

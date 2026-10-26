<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave_grupo  = trim($_POST['clave_grupo'] ?? '');
    $nombre_grupo = trim($_POST['descripcion'] ?? ''); 

    if (!empty($clave_grupo) && !empty($nombre_grupo)) {
        $stmt = $conexion->prepare("INSERT INTO grupos (clave_grupo, nombre_grupo, estatus_grupo) VALUES (?, ?, 'ALTA')");
        if ($stmt) {
            $stmt->bind_param("ss", $clave_grupo, $nombre_grupo);
            if ($stmt->execute()) {
                $mensaje = "<div class='alert alert-success mt-3'>¡Grupo registrado correctamente!</div>";
            } else {
                $mensaje = "<div class='alert alert-danger mt-3'>Error al registrar: " . htmlspecialchars($stmt->error) . "</div>";
            }
            $stmt->close();
        }
    } else {
        $mensaje = "<div class='alert alert-warning mt-3'>Por favor completa la clave y la descripción.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Grupos</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 800px;
            margin: 40px auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
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
        .btn-custom { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-custom:hover { background-color: #881337; color: white; }
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
            <h2>Registro de Grupos</h2>
        </div>
        
        <div class="contenido">
            <?php echo $mensaje; ?>

            <form method="POST" action="reg_grupo.php">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Clave del grupo</label>
                    <input type="text" name="clave_grupo" class="form-control" placeholder="Ej. 406" required>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Descripcion</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="desc." required>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-custom mr-2">Registrar grupo</button>
                    <a href="../crude/crude_grupos.php" class="btn btn-outline-secondary">Regresar al catálogo</a>
                </div>
            </form>
        </div>

        <div class="pie">
            Registro de grupos - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

</body>
</html>

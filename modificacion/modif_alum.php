<?php
include_once("../conexion.php");

$mensaje = "";
$alumno = null;

// Capturar el ID enviado desde el catálogo
if (isset($_GET['idalumn'])) {
    $idalumn = intval($_GET['idalumn']);
    $sql = "SELECT * FROM alumnos WHERE idalumn = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idalumn);
    $stmt->execute();
    $res = $stmt->get_result();
    $alumno = $res->fetch_assoc();
    $stmt->close();
}

// Procesar el envío del formulario al hacer clic en Guardar Cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idalumn          = intval($_POST['idalumn']);
    $numero_control   = $_POST['numero_control'] ?? '';
    $nombre           = $_POST['nombre'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo           = $_POST['correo'] ?? '';

    $sql = "UPDATE alumnos SET matricula_al = ?, nombre_al = ?, apaterno_al = ?, amaterno_al = ?, mail_al = ? WHERE idalumn = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssi", $numero_control, $nombre, $apellido_paterno, $apellido_materno, $correo, $idalumn);
        if ($stmt->execute()) {
            header("Location: ../crude/crude_alumno.php?status=update_ok");
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
    <title>Modificar Alumno - SAE</title>
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
            <h2>Modificar Alumno</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <?php if ($alumno): ?>
            <form method="post" action="modif_alum.php">
                <input type="hidden" name="idalumn" value="<?php echo $alumno['idalumn']; ?>">

                <div class="mb-3">
                    <label class="form-label">Número de control</label>
                    <input type="text" name="numero_control" class="form-control" value="<?php echo htmlspecialchars($alumno['matricula_al']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($alumno['nombre_al']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" value="<?php echo htmlspecialchars($alumno['apaterno_al']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido materno</label>
                    <input type="text" name="apellido_materno" class="form-control" value="<?php echo htmlspecialchars($alumno['amaterno_al']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($alumno['mail_al']); ?>" required>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-sae">Guardar Cambios</button>
                    <a href="../crude/crude_alumno.php" class="btn btn-outline-secondary">Cancelar y regresar al catálogo</a>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-warning">No se encontró la información del alumno especificado.</div>
                <a href="../crude/crude_alumno.php" class="btn btn-secondary">Regresar al catálogo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

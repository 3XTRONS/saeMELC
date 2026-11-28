<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $desc = trim($_POST["descripcion_puesto"]);
    $stmt = $conexion->prepare("INSERT INTO puesto (DESCRIPCION_PUESTO, STATUS_PUESTO) VALUES (?, 'ALTA')");
    $stmt->bind_param("s", $desc);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_puestos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Puesto - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Registrar Puesto</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del puesto</label>
                    <input type="text" name="descripcion_puesto" class="form-control" required placeholder="Ej. Coordinador">
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Guardar Puesto</button>
                    <a href="<?= $base ?>/crude/crude_puestos.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Registro de Puestos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

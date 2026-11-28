<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mat  = trim($_POST["matricula"]);
    $nom  = trim($_POST["nombre"]);
    $apat = trim($_POST["apaterno"]);
    $amat = trim($_POST["amaterno"]);
    $dom  = trim($_POST["domicilio"]);
    $mail = trim($_POST["correo"]);
    $tel  = trim($_POST["telefono"]);

    $stmt = $conexion->prepare("INSERT INTO alumnos (MATRICULA, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, DOMICILIO, CORREO, TELEFONO, ESTATUS) VALUES (?, ?, ?, ?, ?, ?, ?, 'ALTA')");
    $stmt->bind_param("sssssss", $mat, $nom, $apat, $amat, $dom, $mail, $tel);
    if ($stmt->execute()) {
        header("Location: " . $base . "/crude/crude_alumno.php");
        exit;
    }
    $error = "Error al registrar el alumno.";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/../navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Registrar Alumno</h2>
        </div>
        <div class="card-body">
            <?php if ($error !== "") { ?><div class="alert alert-danger"><?= h($error) ?></div><?php } ?>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Matrícula</label><input type="text" name="matricula" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido paterno</label><input type="text" name="apaterno" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido materno</label><input type="text" name="amaterno" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" required></div>
                    <div class="col-12"><label class="form-label">Domicilio</label><input type="text" name="domicilio" class="form-control" required></div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Guardar Alumno</button>
                    <a href="<?= $base ?>/crude/crude_alumno.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Registro de Alumnos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

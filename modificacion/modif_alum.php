<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mat  = trim($_POST["matricula"]);
    $nom  = trim($_POST["nombre"]);
    $apat = trim($_POST["apaterno"]);
    $amat = trim($_POST["amaterno"]);
    $dom  = trim($_POST["domicilio"]);
    $mail = trim($_POST["correo"]);
    $tel  = trim($_POST["telefono"]);

    $stmt = $conexion->prepare("UPDATE alumnos SET MATRICULA=?, NOMBRE=?, APELLIDO_PATERNO=?, APELLIDO_MATERNO=?, DOMICILIO=?, CORREO=?, TELEFONO=? WHERE ID_ALUMNOS=?");
    $stmt->bind_param("sssssssi", $mat, $nom, $apat, $amat, $dom, $mail, $tel, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_alumno.php");
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM alumnos WHERE ID_ALUMNOS = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$al = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$al) { header("Location: " . $base . "/crude/crude_alumno.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/../navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Editar Alumno</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $al['ID_ALUMNOS'] ?>">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Matrícula</label><input type="text" name="matricula" class="form-control" value="<?= h($al['MATRICULA']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" value="<?= h($al['NOMBRE']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido paterno</label><input type="text" name="apaterno" class="form-control" value="<?= h($al['APELLIDO_PATERNO']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido materno</label><input type="text" name="amaterno" class="form-control" value="<?= h($al['APELLIDO_MATERNO']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control" value="<?= h($al['CORREO']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?= h($al['TELEFONO']) ?>" required></div>
                    <div class="col-12"><label class="form-label">Domicilio</label><input type="text" name="domicilio" class="form-control" value="<?= h($al['DOMICILIO']) ?>" required></div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Actualizar Alumno</button>
                    <a href="<?= $base ?>/crude/crude_alumno.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Modificación de Alumnos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

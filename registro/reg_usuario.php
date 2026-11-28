<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$puestos = $conexion->query("SELECT * FROM puesto WHERE STATUS_PUESTO = 'ALTA'");
$profes  = $conexion->query("SELECT * FROM profesores WHERE ESTATUS = 'ALTA'");
$alumnos = $conexion->query("SELECT * FROM alumnos WHERE ESTATUS = 'ALTA'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user   = trim($_POST["nombreuser"]);
    $pass   = sha1($_POST["pwuser"]);
    $puesto = (int)$_POST["puesto"];
    $idProf = !empty($_POST["id_profesor"]) ? (int)$_POST["id_profesor"] : null;
    $idAlum = !empty($_POST["id_alumno"]) ? (int)$_POST["id_alumno"] : null;

    $stmt = $conexion->prepare("INSERT INTO usuarios (NOMBREUSER, PWUSER, PUESTO, ID_PROFESORES, ID_ALUMNOS, STATUS_USER) VALUES (?, ?, ?, ?, ?, 'ALTA')");
    $stmt->bind_param("ssiii", $user, $pass, $puesto, $idProf, $idAlum);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_usuarios.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Registrar Usuario</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Usuario</label><input type="text" name="nombreuser" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Contraseña</label><input type="password" name="pwuser" class="form-control" required></div>
                    <div class="col-md-4">
                        <label class="form-label">Puesto</label>
                        <select name="puesto" class="form-select" required>
                            <?php while ($p = $puestos->fetch_assoc()) { ?>
                                <option value="<?= $p['ID_PUESTO'] ?>"><?= h($p['DESCRIPCION_PUESTO']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vincular a Profesor (opcional)</label>
                        <select name="id_profesor" class="form-select">
                            <option value="">-- Ninguno --</option>
                            <?php while ($pr = $profes->fetch_assoc()) { ?>
                                <option value="<?= $pr['ID_PROFESORES'] ?>"><?= h($pr['CLAVE'] . " - " . $pr['NOMBRE'] . " " . $pr['APELLIDO_PATERNO']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vincular a Alumno (opcional)</label>
                        <select name="id_alumno" class="form-select">
                            <option value="">-- Ninguno --</option>
                            <?php while ($al = $alumnos->fetch_assoc()) { ?>
                                <option value="<?= $al['ID_ALUMNOS'] ?>"><?= h($al['MATRICULA'] . " - " . $al['NOMBRE'] . " " . $al['APELLIDO_PATERNO']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Guardar Usuario</button>
                    <a href="<?= $base ?>/crude/crude_usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Registro de Usuarios - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clave = trim($_POST["clave_materia"]);
    $nom   = trim($_POST["nombre_materia"]);
    $cred  = trim($_POST["creditos"]);
    $desc  = trim($_POST["descripcion"]);

    $stmt = $conexion->prepare("UPDATE materias SET CLAVE_MATERIA=?, NOMBRE_MATERIA=?, CREDITOS=?, DESCRIPCION=? WHERE ID_MATERIAS=?");
    $stmt->bind_param("ssssi", $clave, $nom, $cred, $desc, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_materias.php");
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM materias WHERE ID_MATERIAS = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mat = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$mat) { header("Location: " . $base . "/crude/crude_materias.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Materia - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Editar Materia</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $mat['ID_MATERIAS'] ?>">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Clave</label><input type="text" name="clave_materia" class="form-control" value="<?= h($mat['CLAVE_MATERIA']) ?>" required></div>
                    <div class="col-md-5"><label class="form-label">Nombre de la materia</label><input type="text" name="nombre_materia" class="form-control" value="<?= h($mat['NOMBRE_MATERIA']) ?>" required></div>
                    <div class="col-md-3"><label class="form-label">Créditos</label><input type="number" name="creditos" class="form-control" value="<?= h($mat['CREDITOS']) ?>" required></div>
                    <div class="col-12"><label class="form-label">Descripción</label><input type="text" name="descripcion" class="form-control" value="<?= h($mat['DESCRIPCION']) ?>"></div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Actualizar Materia</button>
                    <a href="<?= $base ?>/crude/crude_materias.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Modificación de Materias - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom  = trim($_POST["nombre_grupo"]);
    $tur  = trim($_POST["turno"]);
    $sem  = trim($_POST["semestre"]);
    $desc = trim($_POST["descripcion"]);

    $stmt = $conexion->prepare("UPDATE grupos SET NOMBRE_GRUPO=?, TURNO=?, SEMESTRE=?, DESCRIPCION=? WHERE ID_GRUPOS=?");
    $stmt->bind_param("ssssi", $nom, $tur, $sem, $desc, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_grupos.php");
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM grupos WHERE ID_GRUPOS = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$gr = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$gr) { header("Location: " . $base . "/crude/crude_grupos.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grupo - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/../navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Editar Grupo</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $gr['ID_GRUPOS'] ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nombre del Grupo</label><input type="text" name="nombre_grupo" class="form-control" value="<?= h($gr['NOMBRE_GRUPO']) ?>" required></div>
                    <div class="col-md-3">
                        <label class="form-label">Turno</label>
                        <select name="turno" class="form-select">
                            <option value="Matutino" <?= $gr['TURNO'] === 'Matutino' ? 'selected' : '' ?>>Matutino</option>
                            <option value="Vespertino" <?= $gr['TURNO'] === 'Vespertino' ? 'selected' : '' ?>>Vespertino</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Semestre</label><input type="text" name="semestre" class="form-control" value="<?= h($gr['SEMESTRE']) ?>" required></div>
                    <div class="col-12"><label class="form-label">Descripción</label><input type="text" name="descripcion" class="form-control" value="<?= h($gr['DESCRIPCION']) ?>"></div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Actualizar Grupo</button>
                    <a href="<?= $base ?>/crude/crude_grupos.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Modificación de Grupos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

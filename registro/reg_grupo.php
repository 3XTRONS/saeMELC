<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom  = trim($_POST["nombre_grupo"]);
    $tur  = trim($_POST["turno"]);
    $sem  = trim($_POST["semestre"]);
    $desc = trim($_POST["descripcion"]);

    $stmt = $conexion->prepare("INSERT INTO grupos (NOMBRE_GRUPO, TURNO, SEMESTRE, DESCRIPCION, ESTATUS) VALUES (?, ?, ?, ?, 'ALTA')");
    $stmt->bind_param("ssss", $nom, $tur, $sem, $desc);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $base . "/crude/crude_grupos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Grupo - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/../navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Registrar Grupo</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nombre del Grupo</label><input type="text" name="nombre_grupo" class="form-control" required placeholder="Ej. 406 - Tecnologías de la Información"></div>
                    <div class="col-md-3">
                        <label class="form-label">Turno</label>
                        <select name="turno" class="form-select">
                            <option value="Matutino">Matutino</option>
                            <option value="Vespertino">Vespertino</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Semestre</label><input type="text" name="semestre" class="form-control" required placeholder="Ej. 4"></div>
                    <div class="col-12"><label class="form-label">Descripción</label><input type="text" name="descripcion" class="form-control"></div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">Guardar Grupo</button>
                    <a href="<?= $base ?>/crude/crude_grupos.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted">Registro de Grupos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

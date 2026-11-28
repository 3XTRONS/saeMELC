<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

if (isset($_GET["baja"])) {
    $id = (int)$_GET["baja"];
    $stmt = $conexion->prepare("UPDATE puesto SET STATUS_PUESTO = 'BAJA' WHERE ID_PUESTO = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: crude_puestos.php");
    exit;
}

$res = $conexion->query("SELECT * FROM puesto WHERE STATUS_PUESTO = 'ALTA' ORDER BY ID_PUESTO ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Puestos - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Catálogo de Puestos</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= $base ?>/registro/reg_puesto.php" class="btn btn-sae">+ Nuevo Puesto</a>
                <a href="<?= $base ?>/principal.php" class="btn btn-outline-secondary">Regresar</a>
            </div>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Descripción del Puesto</th><th class="text-center">Acciones</th></tr>
                </thead>
                <tbody>
                    <?php while ($f = $res->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $f['ID_PUESTO'] ?></td>
                        <td><?= h($f['DESCRIPCION_PUESTO']) ?></td>
                        <td class="text-center">
                            <a href="crude_puestos.php?baja=<?= $f['ID_PUESTO'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja este puesto?');">Baja</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center text-muted">Catálogo de Puestos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

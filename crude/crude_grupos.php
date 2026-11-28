<?php
require_once __DIR__ . "/../conexion.php";
requerirRol(['ADMINISTRADOR']);

$res = $conexion->query("SELECT * FROM grupos WHERE ESTATUS = 'ALTA' ORDER BY ID_GRUPOS ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Grupos - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/../navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Catálogo de Grupos</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= $base ?>/registro/reg_grupo.php" class="btn btn-sae">+ Nuevo Grupo</a>
                <a href="<?= $base ?>/principal.php" class="btn btn-outline-secondary">Regresar</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Nombre del Grupo</th><th>Turno</th><th>Semestre</th><th>Descripción</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($f = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $f['ID_GRUPOS'] ?></td>
                            <td><?= h($f['NOMBRE_GRUPO']) ?></td>
                            <td><?= h($f['TURNO']) ?></td>
                            <td><?= h($f['SEMESTRE']) ?></td>
                            <td><?= h($f['DESCRIPCION']) ?></td>
                            <td class="text-center">
                                <a href="<?= $base ?>/modificacion/modif_grupo.php?id=<?= $f['ID_GRUPOS'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="<?= $base ?>/baja/baja_grupo.php?id=<?= $f['ID_GRUPOS'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja este grupo?');">Baja</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center text-muted">Catálogo de Grupos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

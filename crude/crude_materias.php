<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$res = $conexion->query("SELECT * FROM materias WHERE ESTATUS = 'ALTA' ORDER BY ID_MATERIAS ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Materias - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Catálogo de Materias</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= $base ?>/registro/reg_materia.php" class="btn btn-sae">+ Nueva Materia</a>
                <a href="<?= $base ?>/principal.php" class="btn btn-outline-secondary">Regresar</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Clave</th><th>Materia</th><th>Créditos</th><th>Descripción</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($f = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $f['ID_MATERIAS'] ?></td>
                            <td><?= h($f['CLAVE_MATERIA']) ?></td>
                            <td><?= h($f['NOMBRE_MATERIA']) ?></td>
                            <td><?= h($f['CREDITOS']) ?></td>
                            <td><?= h($f['DESCRIPCION']) ?></td>
                            <td class="text-center">
                                <a href="<?= $base ?>/modificacion/modif_materia.php?id=<?= $f['ID_MATERIAS'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="<?= $base ?>/baja/baja_materia.php?id=<?= $f['ID_MATERIAS'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja esta materia?');">Baja</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center text-muted">Catálogo de Materias - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

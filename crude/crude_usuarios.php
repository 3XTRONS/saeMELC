<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

if (isset($_GET["baja"])) {
    $id = (int)$_GET["baja"];
    $stmt = $conexion->prepare("UPDATE usuarios SET STATUS_USER = 'BAJA' WHERE ID_USER = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: crude_usuarios.php");
    exit;
}

$res = $conexion->query(
    "SELECT u.ID_USER, u.NOMBREUSER, p.DESCRIPCION_PUESTO,
            CONCAT_WS(' ', pr.NOMBRE, pr.APELLIDO_PATERNO) AS prof,
            CONCAT_WS(' ', al.NOMBRE, al.APELLIDO_PATERNO) AS alum
     FROM usuarios u
     INNER JOIN puesto p ON p.ID_PUESTO = u.PUESTO
     LEFT JOIN profesores pr ON pr.ID_PROFESORES = u.ID_PROFESORES
     LEFT JOIN alumnos al ON al.ID_ALUMNOS = u.ID_ALUMNOS
     WHERE u.STATUS_USER = 'ALTA' ORDER BY u.ID_USER ASC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Usuarios - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Catálogo de Usuarios</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="<?= $base ?>/registro/reg_usuario.php" class="btn btn-sae">+ Nuevo Usuario</a>
                <a href="<?= $base ?>/principal.php" class="btn btn-outline-secondary">Regresar</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Usuario</th><th>Puesto</th><th>Vinculado a</th><th class="text-center">Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($f = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $f['ID_USER'] ?></td>
                            <td><?= h($f['NOMBREUSER']) ?></td>
                            <td><?= h($f['DESCRIPCION_PUESTO']) ?></td>
                            <td><?= h($f['prof'] ?: ($f['alum'] ?: '—')) ?></td>
                            <td class="text-center">
                                <a href="crude_usuarios.php?baja=<?= $f['ID_USER'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja este usuario?');">Baja</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center text-muted">Catálogo de Usuarios - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

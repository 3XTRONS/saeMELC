<?php
require_once __DIR__ . "/conexion.php";
requerirLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . "/navegacion/nav.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Sistema de Administración Escolar (SAE)</h2>
        </div>
        <div class="card-body text-center p-4">
            <h4>Bienvenido, <?= h($_SESSION["nombre_user"]) ?></h4>
            <p class="text-muted mb-4">Perfil activo: <strong><?= h($_SESSION["rol_nombre"]) ?></strong></p>
            <p>Utiliza el menú superior para navegar por los módulos habilitados para tu perfil.</p>

            <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                <?php if (esAdmin()) { ?>
                    <a href="<?= $base ?>/crude/crude_alumno.php" class="btn btn-sae">Ir a Alumnos</a>
                    <a href="<?= $base ?>/procesos/captura_calificaciones.php" class="btn btn-sae">Capturar Calificaciones</a>
                    <a href="<?= $base ?>/reportes/rep_boleta.php" class="btn btn-sae">Consultar Boletas</a>
                <?php } elseif (esMaestro()) { ?>
                    <a href="<?= $base ?>/procesos/captura_calificaciones.php" class="btn btn-sae">Capturar Calificaciones</a>
                    <a href="<?= $base ?>/reportes/rep_calificaciones.php" class="btn btn-sae">Lista de Calificaciones</a>
                <?php } else { ?>
                    <a href="<?= $base ?>/reportes/rep_boleta.php" class="btn btn-sae">Ver Mi Boleta</a>
                <?php } ?>
            </div>
        </div>
        <div class="card-footer text-center text-muted">
            Sistema de Administración Escolar - Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

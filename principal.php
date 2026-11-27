<?php
include_once("conexion.php");
include_once("navegacion/nav.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - SAE</title>
    <style>
        body { background-color: #f3f1f4; }
        .card-inicio { background-color: white; max-width: 850px; margin: 40px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 20px; text-align: center; }
        .contenido { padding: 35px; text-align: center; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block; margin: 5px; }
        .btn-sae:hover { background-color: #881337; color: white; text-decoration: none; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="card-inicio">
        <div class="titulo">
            <h2 class="m-0">Sistema de Administración Escolar (SAE)</h2>
        </div>
        <div class="contenido">
            <h4>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h4>
            <p class="text-muted mb-4">Perfil activo: <b><?php echo htmlspecialchars($_SESSION['nombre_puesto']); ?></b></p>
            <p>Utiliza el menú superior para navegar por los módulos habilitados para tu perfil.</p>
            <div class="mt-4">
                <?php if ((int)$_SESSION['id_puesto'] < 4): ?>
                    <a href="crude/crude_alumno.php" class="btn-sae">Ir a Alumnos</a>
                    <a href="procesos/captura_calificaciones.php" class="btn-sae">Capturar Calificaciones</a>
                    <a href="reportes/rep_boleta.php" class="btn-sae">Consultar Boletas</a>
                <?php else: ?>
                    <a href="reportes/rep_boleta.php" class="btn-sae">Ver Mis Calificaciones</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="pie">Sistema de Administración Escolar - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

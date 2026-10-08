<?php
include_once("../navegacion/header.php");
include_once("../navegacion/nav.php");
include_once("../conexion.php");

// Consultas a las tablas
$tot_alumnos   = $conexion->query("SELECT COUNT(*) as total FROM alumnos")->fetch_assoc()['total'] ?? 0;
$tot_profes    = $conexion->query("SELECT COUNT(*) as total FROM profesores")->fetch_assoc()['total'] ?? 0;
$tot_materias  = $conexion->query("SELECT COUNT(*) as total FROM materias")->fetch_assoc()['total'] ?? 0;
$tot_grupos    = $conexion->query("SELECT COUNT(*) as total FROM grupos")->fetch_assoc()['total'] ?? 0;
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Reporte General del Sistema SAE</h2>
    <div class="row text-center">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white p-3 shadow-sm">
                <h4>Alumnos</h4>
                <p class="display-4"><?php echo $tot_alumnos; ?></p>
                <a href="../crude/crude_alumno.php" class="btn btn-light btn-sm">Ver Catálogo</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white p-3 shadow-sm">
                <h4>Profesores</h4>
                <p class="display-4"><?php echo $tot_profes; ?></p>
                <a href="../crude/crude_profesores.php" class="btn btn-light btn-sm">Ver Catálogo</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark p-3 shadow-sm">
                <h4>Materias</h4>
                <p class="display-4"><?php echo $tot_materias; ?></p>
                <a href="../crude/crude_materias.php" class="btn btn-light btn-sm">Ver Catálogo</a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white p-3 shadow-sm">
                <h4>Grupos</h4>
                <p class="display-4"><?php echo $tot_grupos; ?></p>
                <a href="../crude/crude_grupos.php" class="btn btn-light btn-sm">Ver Catálogo</a>
            </div>
        </div>
    </div>
</div>

<?php include_once("../navegacion/footer.php"); ?>

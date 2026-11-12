<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

if (isset($_GET['baja'])) {
    $id = intval($_GET['baja']);
    $conexion->query("UPDATE puestos SET estatus_puesto = 'BAJA' WHERE id_puesto = $id");
    header("Location: crude_puestos.php");
    exit;
}

$resultado = $conexion->query("SELECT id_puesto, nombre_puesto FROM puestos WHERE estatus_puesto = 'ALTA'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Puestos</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 900px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 25px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-sae:hover { background-color: #881337; color: white; text-decoration: none; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="formulario">
        <div class="titulo"><h2>Catálogo de Puestos</h2></div>
        <div class="contenido">
            <div class="mb-4">
                <a href="../registro/reg_puesto.php" class="btn-sae">+ Nuevo Puesto</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle m-0">
                    <thead style="background-color: #212529; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Puesto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><b><?php echo $row['id_puesto']; ?></b></td>
                                <td><?php echo htmlspecialchars($row['nombre_puesto']); ?></td>
                                <td>
                                    <a href="crude_puestos.php?baja=<?php echo $row['id_puesto']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja este puesto?');">Baja</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">No hay puestos activos.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pie">Catálogo de puestos - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

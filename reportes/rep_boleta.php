<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$idalumn = intval($_GET['idalumn'] ?? 0);
$alumnos_select = $conexion->query("SELECT idalumn, matricula_al, CONCAT(nombre_al, ' ', apaterno_al, ' ', amaterno_al) AS alumno FROM alumnos WHERE estatus_al = 'ALTA'");
$boleta = null;
$promedio = 0;

if ($idalumn > 0) {
    $sql = "SELECT u.nombre_unidad, c.calificacion
            FROM calificaciones c
            INNER JOIN unidades u ON c.id_unidad = u.id_unidad
            WHERE c.idalumn = $idalumn";
    $boleta = $conexion->query($sql);
    
    $prom = $conexion->query("SELECT AVG(calificacion) as promedio FROM calificaciones WHERE idalumn = $idalumn");
    if ($prom) {
        $p = $prom->fetch_assoc();
        $promedio = $p['promedio'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Boleta de Calificaciones</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 800px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 25px; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
        @media print { nav, .navbar, .btn, form, .pie, .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo"><h2 class="m-0">Boleta de Calificaciones</h2></div>
        <div class="contenido">
            <form method="GET" action="rep_boleta.php" class="row align-items-end mb-4 no-print">
                <div class="col-md-9 mb-2">
                    <label class="font-weight-bold">Selecciona Alumno</label>
                    <select name="idalumn" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php if ($alumnos_select): ?>
                            <?php while ($al = $alumnos_select->fetch_assoc()): ?>
                                <option value="<?php echo $al['idalumn']; ?>" <?php echo ($idalumn == $al['idalumn']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($al['matricula_al'] . ' - ' . $al['alumno']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-secondary w-100">Generar Boleta</button>
                </div>
            </form>

            <?php if ($idalumn > 0): ?>
                <div class="d-flex justify-content-end mb-3 no-print">
                    <button onclick="window.print();" class="btn btn-outline-dark">🖨️ Imprimir Boleta</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #212529; color: white;">
                            <tr>
                                <th>Unidad Evaluada</th>
                                <th class="text-center">Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($boleta && $boleta->num_rows > 0): ?>
                                <?php while ($row = $boleta->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombre_unidad']); ?></td>
                                        <td class="text-center"><b><?php echo number_format($row['calificacion'], 2); ?></b></td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr style="background-color: #e2e8f0;">
                                    <td><b>PROMEDIO GENERAL</b></td>
                                    <td class="text-center"><b class="text-primary"><?php echo number_format($promedio, 2); ?></b></td>
                                </tr>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center py-3 text-muted">No existen registros de calificaciones para este alumno.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="pie">Boleta escolar - Emmanuel Lopez Cornejo</div>
    </div>
</div>

</body>
</html>

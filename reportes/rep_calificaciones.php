<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$idmat = intval($_GET['idmat'] ?? 0);
$idgrupo = intval($_GET['idgrupo'] ?? 0);

$materias_select = $conexion->query("SELECT idmat, clave_mat, nombre_mat FROM materias WHERE estatus_mat = 'ALTA'");
$grupos_select = $conexion->query("SELECT idgrupo, clave_grupo, nombre_grupo FROM grupos WHERE estatus_grupo = 'ALTA'");

$reporte = null;

if ($idmat > 0 && $idgrupo > 0) {
    $sql = "SELECT a.idalumn, a.matricula_al, CONCAT(a.nombre_al, ' ', a.apaterno_al, ' ', a.amaterno_al) AS alumno,
                   u.nombre_unidad, c.calificacion
            FROM grupo_alumnos ga
            INNER JOIN alumnos a ON ga.idalumn = a.idalumn
            INNER JOIN calificaciones c ON a.idalumn = c.idalumn
            INNER JOIN unidades u ON c.id_unidad = u.id_unidad
            WHERE ga.idgrupo = $idgrupo AND a.estatus_al = 'ALTA'
            ORDER BY a.idalumn, u.id_unidad";
    $reporte = $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Calificaciones por Materia y Grupo</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 950px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 25px; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
        @media print { nav, .navbar, .btn, form, .pie, .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo"><h2 class="m-0">Reporte: Calificaciones por Materia y Grupo</h2></div>
        <div class="contenido">
            <form method="GET" action="rep_calificaciones.php" class="row align-items-end mb-4 no-print">
                <div class="col-md-5 mb-2">
                    <label class="font-weight-bold">Materia</label>
                    <select name="idmat" class="form-control" required>
                        <option value="">Selecciona materia...</option>
                        <?php if ($materias_select): ?>
                            <?php while ($m = $materias_select->fetch_assoc()): ?>
                                <option value="<?php echo $m['idmat']; ?>" <?php echo ($idmat == $m['idmat']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($m['clave_mat'] . ' - ' . $m['nombre_mat']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-5 mb-2">
                    <label class="font-weight-bold">Grupo</label>
                    <select name="idgrupo" class="form-control" required>
                        <option value="">Selecciona grupo...</option>
                        <?php if ($grupos_select): ?>
                            <?php while ($g = $grupos_select->fetch_assoc()): ?>
                                <option value="<?php echo $g['idgrupo']; ?>" <?php echo ($idgrupo == $g['idgrupo']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($g['clave_grupo'] . ' - ' . $g['nombre_grupo']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-secondary w-100">Consultar</button>
                </div>
            </form>

            <?php if ($idmat > 0 && $idgrupo > 0): ?>
                <div class="d-flex justify-content-end mb-3 no-print">
                    <button onclick="window.print();" class="btn btn-outline-dark">🖨️ Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead style="background-color: #212529; color: white;">
                            <tr>
                                <th>Matrícula</th>
                                <th>Alumno</th>
                                <th>Unidad</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reporte && $reporte->num_rows > 0): ?>
                                <?php while ($row = $reporte->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['matricula_al']); ?></td>
                                        <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nombre_unidad']); ?></td>
                                        <td><b><?php echo number_format($row['calificacion'], 2); ?></b></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">No se encontraron calificaciones asociadas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="pie">Reporte de calificaciones por materia y grupo - Emmanuel Lopez Cornejo</div>
    </div>
</div>

</body>
</html>

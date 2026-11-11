<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$idalumn = intval($_GET['idalumn'] ?? 0);
$alumnos_select = $conexion->query("SELECT idalumn, matricula_al, CONCAT(nombre_al, ' ', apaterno_al, ' ', amaterno_al) AS alumno FROM alumnos WHERE estatus_al = 'ALTA'");
$materias = null;

if ($idalumn > 0) {
    $sql = "SELECT m.clave_mat, m.nombre_mat, m.creditos, CONCAT(p.nombre_prof, ' ', p.apaterno_prof) AS profesor
            FROM asignaciones a
            INNER JOIN materias m ON a.idmat = m.idmat
            INNER JOIN profesores p ON a.idprof = p.idprof
            WHERE a.idalumn = $idalumn AND m.estatus_mat = 'ALTA'";
    $materias = $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Materias por Alumno</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 900px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 25px; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
        @media print { nav, .navbar, .btn, form, .pie, .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo"><h2 class="m-0">Reporte: Materias por Alumno</h2></div>
        <div class="contenido">
            <form method="GET" action="rep_materias_alumno.php" class="row align-items-end mb-4 no-print">
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
                    <button type="submit" class="btn btn-secondary w-100">Consultar</button>
                </div>
            </form>

            <?php if ($idalumn > 0): ?>
                <div class="d-flex justify-content-end mb-3 no-print">
                    <button onclick="window.print();" class="btn btn-outline-dark">🖨️ Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead style="background-color: #212529; color: white;">
                            <tr>
                                <th>Clave</th>
                                <th>Materia</th>
                                <th>Créditos</th>
                                <th>Profesor Asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($materias && $materias->num_rows > 0): ?>
                                <?php while ($row = $materias->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['clave_mat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nombre_mat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['creditos']); ?></td>
                                        <td><?php echo htmlspecialchars($row['profesor']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">El alumno no tiene materias asignadas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="pie">Reporte de materias por alumno - Emmanuel Lopez Cornejo</div>
    </div>
</div>

</body>
</html>

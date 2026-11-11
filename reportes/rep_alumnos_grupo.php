<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$idgrupo = intval($_GET['idgrupo'] ?? 0);
$grupos = $conexion->query("SELECT idgrupo, clave_grupo, nombre_grupo FROM grupos WHERE estatus_grupo = 'ALTA'");
$alumnos = null;

if ($idgrupo > 0) {
    $sql = "SELECT a.idalumn, a.matricula_al, CONCAT(a.nombre_al, ' ', a.apaterno_al, ' ', a.amaterno_al) AS alumno, a.mail_al
            FROM grupo_alumnos ga
            INNER JOIN alumnos a ON ga.idalumn = a.idalumn
            WHERE ga.idgrupo = $idgrupo AND a.estatus_al = 'ALTA'";
    $alumnos = $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Alumnos por grupo</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 900px;
            margin: 30px auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .titulo {
            background-color: #172554;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .contenido { padding: 25px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
        @media print { nav, .navbar, .btn, form, .pie, .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2 class="m-0">Reporte: Alumnos por Grupo</h2>
        </div>

        <div class="contenido">
            <form method="GET" action="rep_alumnos_grupo.php" class="row align-items-end mb-4 no-print">
                <div class="col-md-9 mb-2">
                    <label class="font-weight-bold">Selecciona Grupo</label>
                    <select name="idgrupo" class="form-control" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php if ($grupos): ?>
                            <?php while ($g = $grupos->fetch_assoc()): ?>
                                <option value="<?php echo $g['idgrupo']; ?>" <?php echo ($idgrupo == $g['idgrupo']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($g['clave_grupo'] . ' - ' . $g['nombre_grupo']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-secondary w-100">Generar Reporte</button>
                </div>
            </form>

            <?php if ($idgrupo > 0): ?>
                <div class="d-flex justify-content-end mb-3 no-print">
                    <button onclick="window.print();" class="btn btn-outline-dark">🖨️ Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead style="background-color: #212529; color: white;">
                            <tr>
                                <th>#</th>
                                <th>Matrícula</th>
                                <th>Alumno</th>
                                <th>Correo Electrónico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($alumnos && $alumnos->num_rows > 0): ?>
                                <?php $i = 1; ?>
                                <?php while ($row = $alumnos->fetch_assoc()): ?>
                                    <tr>
                                        <td><b><?php echo $i++; ?></b></td>
                                        <td><?php echo htmlspecialchars($row['matricula_al']); ?></td>
                                        <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                        <td><?php echo htmlspecialchars($row['mail_al']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">No hay alumnos inscritos en este grupo.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="pie">Reporte de alumnos por grupo - Emmanuel Lopez Cornejo</div>
    </div>
</div>

</body>
</html>

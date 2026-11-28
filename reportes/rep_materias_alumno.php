<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$idAlumno = (int)($_GET["id_alumno"] ?? 0);
$alumnos = $conexion->query("SELECT ID_ALUMNOS, MATRICULA, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO FROM alumnos WHERE ESTATUS = 'ALTA' ORDER BY APELLIDO_PATERNO, NOMBRE")->fetch_all(MYSQLI_ASSOC);

$alumnoSel = null;
$filas = [];

if ($idAlumno > 0) {
    $stmt = $conexion->prepare("SELECT * FROM alumnos WHERE ID_ALUMNOS = ?");
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $alumnoSel = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    if ($alumnoSel !== null) {
        $stmt = $conexion->prepare(
            "SELECT m.CLAVE_MATERIA, m.NOMBRE_MATERIA, m.CREDITOS,
                    CONCAT(p.NOMBRE, ' ', p.APELLIDO_PATERNO, ' ', p.APELLIDO_MATERNO) AS PROFESOR
             FROM asignatura s
             INNER JOIN materias m ON m.ID_MATERIAS = s.ID_MATERIAS
             INNER JOIN profesores p ON p.ID_PROFESORES = s.ID_PROFESORES
             WHERE s.ID_ALUMNOS = ?
             ORDER BY m.CLAVE_MATERIA"
        );
        $stmt->bind_param("i", $idAlumno);
        $stmt->execute();
        $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

if (isset($_GET["descargar"]) && $alumnoSel !== null && count($filas) > 0) {
    $nomAl = $alumnoSel["MATRICULA"] . " - " . $alumnoSel["NOMBRE"] . " " . $alumnoSel["APELLIDO_PATERNO"] . " " . $alumnoSel["APELLIDO_MATERNO"];
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"reporte_materias_alumno_" . (int)$idAlumno . ".xls\"");
    echo "\xEF\xBB\xBF<table border='1'>";
    echo "<tr><th colspan='5'>Reporte: Materias por Alumno — " . h($nomAl) . "</th></tr>";
    echo "<tr><th>#</th><th>Clave</th><th>Materia</th><th>Créditos</th><th>Profesor</th></tr>";
    foreach ($filas as $i => $f) {
        echo "<tr><td>" . ($i + 1) . "</td><td>" . h($f["CLAVE_MATERIA"]) . "</td><td>" . h($f["NOMBRE_MATERIA"]) . "</td><td>" . h($f["CREDITOS"]) . "</td><td>" . h($f["PROFESOR"]) . "</td></tr>";
    }
    echo "</table>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte: Materias por Alumno - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center py-3">
            <h2 class="mb-0">Reporte: Materias por Alumno</h2>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="mb-4 no-imprimir">
                <label class="form-label fw-bold">Selecciona Alumno</label>
                <div class="row g-2">
                    <div class="col-md-9">
                        <select name="id_alumno" class="form-select" required>
                            <option value="">Selecciona un alumno...</option>
                            <?php foreach ($alumnos as $a) { ?>
                                <option value="<?= $a["ID_ALUMNOS"] ?>" <?= $idAlumno === (int)$a["ID_ALUMNOS"] ? "selected" : "" ?>>
                                    <?= h($a["MATRICULA"] . " - " . $a["NOMBRE"] . " " . $a["APELLIDO_PATERNO"] . " " . $a["APELLIDO_MATERNO"]) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary w-100">Generar Reporte</button>
                    </div>
                </div>
            </form>

            <?php if ($alumnoSel !== null) { ?>
                <div class="d-flex justify-content-end gap-2 mb-3 no-imprimir">
                    <?php if (count($filas) > 0) { ?>
                        <a href="?id_alumno=<?= $idAlumno ?>&descargar=1" class="btn btn-success btn-sm">Descargar Excel</a>
                    <?php } ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Clave</th>
                                <th>Materia</th>
                                <th>Créditos</th>
                                <th>Profesor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filas) === 0) { ?>
                                <tr><td colspan="5" class="text-center text-muted">Este alumno no tiene materias asignadas.</td></tr>
                            <?php } ?>
                            <?php foreach ($filas as $i => $f) { ?>
                                <tr>
                                    <td class="fw-bold"><?= $i + 1 ?></td>
                                    <td><?= h($f["CLAVE_MATERIA"]) ?></td>
                                    <td><?= h($f["NOMBRE_MATERIA"]) ?></td>
                                    <td><?= h($f["CREDITOS"]) ?></td>
                                    <td><?= h($f["PROFESOR"]) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
        <div class="card-footer text-center text-muted small">
            Reporte de materias por alumno - Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

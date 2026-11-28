<?php
include_once("../conexion.php");
requerirLogin();

$esAl = esAlumno();
$idAlumno = $esAl ? (int)($_SESSION["id_alumno"] ?? 0) : (int)($_GET["id_alumno"] ?? 0);

$alumnos = [];
if (!$esAl) {
    $alumnos = $conexion->query("SELECT ID_ALUMNOS, MATRICULA, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO FROM alumnos WHERE ESTATUS = 'ALTA' ORDER BY APELLIDO_PATERNO, NOMBRE")->fetch_all(MYSQLI_ASSOC);
}

$unidades = $conexion->query("SELECT ID_UNIDAD, DESCRIPCION FROM unidad WHERE ESTATUS = 'ALTA' ORDER BY ID_UNIDAD")->fetch_all(MYSQLI_ASSOC);
$alumnoSel = null;
$filas = [];
$promedioGeneral = null;

if ($idAlumno > 0) {
    $stmt = $conexion->prepare("SELECT * FROM alumnos WHERE ID_ALUMNOS = ?");
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $alumnoSel = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    if ($alumnoSel !== null) {
        $stmt = $conexion->prepare(
            "SELECT m.ID_MATERIAS, m.CLAVE_MATERIA, m.NOMBRE_MATERIA,
                    CONCAT(p.NOMBRE, ' ', p.APELLIDO_PATERNO) AS PROFESOR
             FROM asignatura s
             INNER JOIN materias m ON m.ID_MATERIAS = s.ID_MATERIAS
             INNER JOIN profesores p ON p.ID_PROFESORES = s.ID_PROFESORES
             WHERE s.ID_ALUMNOS = ?
             ORDER BY m.CLAVE_MATERIA"
        );
        $stmt->bind_param("i", $idAlumno);
        $stmt->execute();
        $mats = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmtCal = $conexion->prepare("SELECT ID_UNIDAD, CALIFICACION FROM calificaciones WHERE ID_ALUMNOS = ? AND ID_MATERIAS = ?");
        $promediosMaterias = [];

        foreach ($mats as $m) {
            $idMat = (int)$m["ID_MATERIAS"];
            $stmtCal->bind_param("ii", $idAlumno, $idMat);
            $stmtCal->execute();
            $resCal = $stmtCal->get_result();
            $notas = [];
            while ($rc = $resCal->fetch_assoc()) {
                $notas[(int)$rc["ID_UNIDAD"]] = (float)$rc["CALIFICACION"];
            }
            $m["notas"] = $notas;
            $m["promedio"] = count($notas) > 0 ? round(array_sum($notas) / count($notas), 2) : null;
            if ($m["promedio"] !== null) {
                $promediosMaterias[] = $m["promedio"];
            }
            $filas[] = $m;
        }
        $stmtCal->close();

        if (count($promediosMaterias) > 0) {
            $promedioGeneral = round(array_sum($promediosMaterias) / count($promediosMaterias), 2);
        }
    }
}

if (isset($_GET["descargar"]) && $alumnoSel !== null && count($filas) > 0) {
    $nomAl = $alumnoSel["MATRICULA"] . " - " . $alumnoSel["NOMBRE"] . " " . $alumnoSel["APELLIDO_PATERNO"] . " " . $alumnoSel["APELLIDO_MATERNO"];
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"boleta_alumno_" . (int)$idAlumno . ".xls\"");
    echo "\xEF\xBB\xBF<table border='1'>";
    echo "<tr><th colspan='" . (5 + count($unidades)) . "'>Boleta de Calificaciones — " . h($nomAl) . "</th></tr>";
    echo "<tr><th>#</th><th>Materia</th><th>Profesor</th>";
    foreach ($unidades as $u) { echo "<th>" . h($u["DESCRIPCION"]) . "</th>"; }
    echo "<th>Promedio</th></tr>";
    foreach ($filas as $i => $f) {
        echo "<tr><td>" . ($i + 1) . "</td><td>" . h($f["CLAVE_MATERIA"] . " - " . $f["NOMBRE_MATERIA"]) . "</td><td>" . h($f["PROFESOR"]) . "</td>";
        foreach ($unidades as $u) {
            $idU = (int)$u["ID_UNIDAD"];
            echo "<td>" . (isset($f["notas"][$idU]) ? $f["notas"][$idU] : "-") . "</td>";
        }
        echo "<td>" . ($f["promedio"] !== null ? $f["promedio"] : "-") . "</td></tr>";
    }
    echo "<tr><td colspan='" . (3 + count($unidades)) . "'><strong>Promedio General</strong></td><td><strong>" . ($promedioGeneral !== null ? $promedioGeneral : "-") . "</strong></td></tr>";
    echo "</table>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte: Boleta de Calificaciones - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center py-3">
            <h2 class="mb-0">Reporte: Boleta de Calificaciones</h2>
        </div>
        <div class="card-body p-4">
            <?php if (!$esAl) { ?>
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
            <?php } ?>

            <?php if ($alumnoSel !== null) { ?>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <strong>Alumno:</strong> <?= h($alumnoSel["MATRICULA"] . " - " . $alumnoSel["NOMBRE"] . " " . $alumnoSel["APELLIDO_PATERNO"] . " " . $alumnoSel["APELLIDO_MATERNO"]) ?>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($promedioGeneral !== null) { ?>
                            <span class="badge badge-sae fs-6">Promedio: <?= h($promedioGeneral) ?></span>
                        <?php } ?>
                        <?php if (count($filas) > 0) { ?>
                            <a href="?id_alumno=<?= $idAlumno ?>&descargar=1" class="btn btn-success btn-sm no-imprimir">Descargar Excel</a>
                        <?php } ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm no-imprimir" onclick="window.print()">Imprimir / Guardar PDF</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Materia</th>
                                <th>Profesor</th>
                                <?php foreach ($unidades as $u) { ?><th><?= h($u["DESCRIPCION"]) ?></th><?php } ?>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filas) === 0) { ?>
                                <tr><td colspan="<?= 4 + count($unidades) ?>" class="text-center text-muted">No hay materias asignadas a este alumno.</td></tr>
                            <?php } ?>
                            <?php foreach ($filas as $i => $f) { ?>
                                <tr>
                                    <td class="fw-bold"><?= $i + 1 ?></td>
                                    <td><?= h($f["CLAVE_MATERIA"] . " - " . $f["NOMBRE_MATERIA"]) ?></td>
                                    <td><?= h($f["PROFESOR"]) ?></td>
                                    <?php foreach ($unidades as $u) {
                                        $idU = (int)$u["ID_UNIDAD"];
                                    ?>
                                        <td><?= isset($f["notas"][$idU]) ? h($f["notas"][$idU]) : "—" ?></td>
                                    <?php } ?>
                                    <td><strong><?= $f["promedio"] !== null ? h($f["promedio"]) : "—" ?></strong></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
        <div class="card-footer text-center text-muted small">
            Boleta de calificaciones - Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

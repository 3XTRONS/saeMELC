<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR', 'MAESTRO']);

$idMateria = (int)($_GET["id_materia"] ?? 0);
$idGrupo   = (int)($_GET["id_grupo"] ?? 0);
$soloMaestro = esMaestro();
$idProfSesion = $soloMaestro ? (int)$_SESSION["id_profesor"] : 0;

if ($soloMaestro) {
    $stmt = $conexion->prepare(
        "SELECT DISTINCT m.ID_MATERIAS, m.CLAVE_MATERIA, m.NOMBRE_MATERIA
         FROM materias m
         INNER JOIN asignatura s ON s.ID_MATERIAS = m.ID_MATERIAS
         WHERE m.ESTATUS = 'ALTA' AND s.ID_PROFESORES = ?
         ORDER BY m.CLAVE_MATERIA"
    );
    $stmt->bind_param("i", $idProfSesion);
    $stmt->execute();
    $materias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $materias = $conexion->query("SELECT ID_MATERIAS, CLAVE_MATERIA, NOMBRE_MATERIA FROM materias WHERE ESTATUS = 'ALTA' ORDER BY CLAVE_MATERIA")->fetch_all(MYSQLI_ASSOC);
}

$grupos   = $conexion->query("SELECT ID_GRUPOS, NOMBRE_GRUPO FROM grupos WHERE ESTATUS = 'ALTA' ORDER BY NOMBRE_GRUPO")->fetch_all(MYSQLI_ASSOC);
$unidades = $conexion->query("SELECT ID_UNIDAD, DESCRIPCION FROM unidad WHERE ESTATUS = 'ALTA' ORDER BY ID_UNIDAD")->fetch_all(MYSQLI_ASSOC);

$materiaSel = null;
$filas = [];

if ($idMateria > 0) {
    foreach ($materias as $mItem) {
        if ((int)$mItem["ID_MATERIAS"] === $idMateria) {
            $materiaSel = $mItem;
            break;
        }
    }

    if ($materiaSel !== null) {
        $sql = "SELECT a.ID_ALUMNOS, a.MATRICULA,
                       CONCAT(a.NOMBRE, ' ', a.APELLIDO_PATERNO, ' ', a.APELLIDO_MATERNO) AS ALUMNO,
                       GROUP_CONCAT(DISTINCT g.NOMBRE_GRUPO ORDER BY g.NOMBRE_GRUPO SEPARATOR ', ') AS GRUPOS
                FROM asignatura s
                INNER JOIN alumnos a ON a.ID_ALUMNOS = s.ID_ALUMNOS
                LEFT JOIN grupo_alumno ga ON ga.ID_ALUMNOS = a.ID_ALUMNOS AND ga.ESTATUS = 'ALTA'
                LEFT JOIN grupos g ON g.ID_GRUPOS = ga.ID_GRUPOS AND g.ESTATUS = 'ALTA'
                WHERE s.ID_MATERIAS = ? AND a.ESTATUS = 'ALTA'";
        if ($soloMaestro) {
            $sql .= " AND s.ID_PROFESORES = " . $idProfSesion;
        }
        if ($idGrupo > 0) {
            $sql .= " AND ga.ID_GRUPOS = " . $idGrupo;
        }
        $sql .= " GROUP BY a.ID_ALUMNOS, a.MATRICULA, a.NOMBRE, a.APELLIDO_PATERNO, a.APELLIDO_MATERNO
                  ORDER BY a.APELLIDO_PATERNO, a.APELLIDO_MATERNO, a.NOMBRE";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idMateria);
        $stmt->execute();
        $alumnosBase = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmtCal = $conexion->prepare("SELECT ID_UNIDAD, CALIFICACION FROM calificaciones WHERE ID_ALUMNOS = ? AND ID_MATERIAS = ?");
        foreach ($alumnosBase as $al) {
            $idAl = (int)$al["ID_ALUMNOS"];
            $stmtCal->bind_param("ii", $idAl, $idMateria);
            $stmtCal->execute();
            $resCal = $stmtCal->get_result();
            $notas = [];
            while ($rc = $resCal->fetch_assoc()) {
                $notas[(int)$rc["ID_UNIDAD"]] = (float)$rc["CALIFICACION"];
            }
            $al["notas"] = $notas;
            $al["promedio"] = count($notas) > 0 ? round(array_sum($notas) / count($notas), 2) : null;
            $filas[] = $al;
        }
        $stmtCal->close();
    }
}

if (isset($_GET["descargar"]) && $materiaSel !== null && count($filas) > 0) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"reporte_calificaciones_" . (int)$idMateria . ".xls\"");
    echo "\xEF\xBB\xBF<table border='1'>";
    echo "<tr><th colspan='" . (5 + count($unidades)) . "'>Reporte: Calificaciones — " . h($materiaSel["CLAVE_MATERIA"] . " - " . $materiaSel["NOMBRE_MATERIA"]) . "</th></tr>";
    echo "<tr><th>#</th><th>Matrícula</th><th>Alumno</th><th>Grupo</th>";
    foreach ($unidades as $u) { echo "<th>" . h($u["DESCRIPCION"]) . "</th>"; }
    echo "<th>Promedio</th></tr>";
    foreach ($filas as $i => $f) {
        echo "<tr><td>" . ($i + 1) . "</td><td>" . h($f["MATRICULA"]) . "</td><td>" . h($f["ALUMNO"]) . "</td><td>" . h($f["GRUPOS"] ?: "—") . "</td>";
        foreach ($unidades as $u) {
            $idU = (int)$u["ID_UNIDAD"];
            echo "<td>" . (isset($f["notas"][$idU]) ? $f["notas"][$idU] : "-") . "</td>";
        }
        echo "<td>" . ($f["promedio"] !== null ? $f["promedio"] : "-") . "</td></tr>";
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
    <title>Reporte: Calificaciones - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center py-3">
            <h2 class="mb-0">Reporte: Calificaciones por Materia</h2>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="row g-2 mb-4 no-imprimir">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Selecciona Materia</label>
                    <select name="id_materia" class="form-select" required>
                        <option value="">Selecciona materia...</option>
                        <?php foreach ($materias as $m) { ?>
                            <option value="<?= $m["ID_MATERIAS"] ?>" <?= $idMateria === (int)$m["ID_MATERIAS"] ? "selected" : "" ?>>
                                <?= h($m["CLAVE_MATERIA"] . " - " . $m["NOMBRE_MATERIA"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Grupo (opcional)</label>
                    <select name="id_grupo" class="form-select">
                        <option value="0">Todos los grupos</option>
                        <?php foreach ($grupos as $g) { ?>
                            <option value="<?= $g["ID_GRUPOS"] ?>" <?= $idGrupo === (int)$g["ID_GRUPOS"] ? "selected" : "" ?>>
                                <?= h($g["NOMBRE_GRUPO"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">Generar Reporte</button>
                </div>
            </form>

            <?php if ($materiaSel !== null) { ?>
                <div class="d-flex justify-content-end gap-2 mb-3 no-imprimir">
                    <?php if (count($filas) > 0) { ?>
                        <a href="?id_materia=<?= $idMateria ?>&id_grupo=<?= $idGrupo ?>&descargar=1" class="btn btn-success btn-sm">Descargar Excel</a>
                    <?php } ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Matrícula</th>
                                <th>Alumno</th>
                                <th>Grupo</th>
                                <?php foreach ($unidades as $u) { ?><th><?= h($u["DESCRIPCION"]) ?></th><?php } ?>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filas) === 0) { ?>
                                <tr><td colspan="<?= 5 + count($unidades) ?>" class="text-center text-muted">No hay alumnos registrados para este filtro.</td></tr>
                            <?php } ?>
                            <?php foreach ($filas as $i => $f) { ?>
                                <tr>
                                    <td class="fw-bold"><?= $i + 1 ?></td>
                                    <td><?= h($f["MATRICULA"]) ?></td>
                                    <td><?= h($f["ALUMNO"]) ?></td>
                                    <td><?= h($f["GRUPOS"] ?: "—") ?></td>
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
            Reporte de calificaciones - Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

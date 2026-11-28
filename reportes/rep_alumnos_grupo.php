<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR', 'MAESTRO']);

$idGrupo = (int)($_GET["id_grupo"] ?? $_GET["idgrupo"] ?? 0);
$soloMaestro = esMaestro();$idProfSesion = $soloMaestro ? (int)$_SESSION["id_profesor"] : 0;

if ($soloMaestro) {
    $stmt =$conexion->prepare(
        "SELECT DISTINCT g.ID_GRUPOS, g.NOMBRE_GRUPO
         FROM grupos g
         INNER JOIN grupo_alumno ga ON ga.ID_GRUPOS = g.ID_GRUPOS AND ga.ESTATUS = 'ALTA'
         INNER JOIN asignatura s ON s.ID_ALUMNOS = ga.ID_ALUMNOS
         WHERE g.ESTATUS = 'ALTA' AND s.ID_PROFESORES = ?
         ORDER BY g.NOMBRE_GRUPO"
    );
    $stmt->bind_param("i", $idProfSesion);
    $stmt->execute();$grupos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();
} else {
    $grupos =$conexion->query("SELECT ID_GRUPOS, NOMBRE_GRUPO FROM grupos WHERE ESTATUS = 'ALTA' ORDER BY NOMBRE_GRUPO")->fetch_all(MYSQLI_ASSOC);
}

$grupo = null;
$filas = [];

if ($idGrupo > 0) {
    $stmt =$conexion->prepare("SELECT ID_GRUPOS, NOMBRE_GRUPO FROM grupos WHERE ID_GRUPOS = ?");
    $stmt->bind_param("i", $idGrupo);$stmt->execute();
    $grupo =$stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    if ($grupo !== null) {
        if ($soloMaestro) {
            $stmt =$conexion->prepare(
                "SELECT DISTINCT a.MATRICULA,
                        CONCAT(a.NOMBRE, ' ', a.APELLIDO_PATERNO, ' ', a.APELLIDO_MATERNO) AS ALUMNO,
                        a.CORREO
                 FROM grupo_alumno ga
                 INNER JOIN alumnos a ON a.ID_ALUMNOS = ga.ID_ALUMNOS
                 INNER JOIN asignatura s ON s.ID_ALUMNOS = a.ID_ALUMNOS AND s.ID_PROFESORES = ?
                 WHERE ga.ID_GRUPOS = ? AND ga.ESTATUS = 'ALTA' AND a.ESTATUS = 'ALTA'
                 ORDER BY a.APELLIDO_PATERNO, a.APELLIDO_MATERNO, a.NOMBRE"
            );
            $stmt->bind_param("ii", $idProfSesion,$idGrupo);
        } else {
            $stmt =$conexion->prepare(
                "SELECT a.MATRICULA,
                        CONCAT(a.NOMBRE, ' ', a.APELLIDO_PATERNO, ' ', a.APELLIDO_MATERNO) AS ALUMNO,
                        a.CORREO
                 FROM grupo_alumno ga
                 INNER JOIN alumnos a ON a.ID_ALUMNOS = ga.ID_ALUMNOS
                 WHERE ga.ID_GRUPOS = ? AND ga.ESTATUS = 'ALTA' AND a.ESTATUS = 'ALTA'
                 ORDER BY a.APELLIDO_PATERNO, a.APELLIDO_MATERNO, a.NOMBRE"
            );
            $stmt->bind_param("i", $idGrupo);
        }
        $stmt->execute();$filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();
    }
}

if (isset($_GET["descargar"]) && $grupo !== null && count($filas) > 0) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"reporte_alumnos_grupo_" . (int)$idGrupo . ".xls\"");
    echo "\xEF\xBB\xBF<table border='1'>";
    echo "<tr><th colspan='4'>Reporte: Alumnos por Grupo — " . h($grupo["NOMBRE_GRUPO"]) . "</th></tr>";
    echo "<tr><th>#</th><th>Matrícula</th><th>Alumno</th><th>Correo Electrónico</th></tr>";
    foreach ($filas as $i =>$f) {
        echo "<tr><td>" . ($i + 1) . "</td><td>" . h($f["MATRICULA"]) . "</td><td>" . h($f["ALUMNO"]) . "</td><td>" . h($f["CORREO"]) . "</td></tr>";
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
    <title>Reporte: Alumnos por Grupo - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center py-3">
            <h2 class="mb-0">Reporte: Alumnos por Grupo</h2>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="mb-4 no-imprimir">
                <label class="form-label fw-bold">Selecciona Grupo</label>
                <div class="row g-2">
                    <div class="col-md-9">
                        <select name="id_grupo" class="form-select" required>
                            <option value="">Selecciona un grupo...</option>
                            <?php foreach ($grupos as$g) { ?>
                                <option value="<?= $g["ID_GRUPOS"] ?>" <?= $idGrupo === (int)$g["ID_GRUPOS"] ? "selected" : "" ?>>
                                    <?= h($g["NOMBRE_GRUPO"]) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary w-100">Generar Reporte</button>
                    </div>
                </div>
            </form>

            <?php if ($grupo !== null) { ?>
                <div class="d-flex justify-content-end gap-2 mb-3 no-imprimir">
                    <?php if (count($filas) > 0) { ?>
                        <a href="?id_grupo=<?= $idGrupo ?>&descargar=1" class="btn btn-success btn-sm">Descargar Excel</a>
                    <?php } ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Imprimir / Guardar PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Matrícula</th>
                                <th>Alumno</th>
                                <th>Correo Electrónico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filas) === 0) { ?>
                                <tr><td colspan="4" class="text-center text-muted">No hay alumnos inscritos en este grupo.</td></tr>
                            <?php } ?>
                            <?php foreach ($filas as $i =>$f) { ?>
                                <tr>
                                    <td class="fw-bold"><?= $i + 1 ?></td>
                                    <td><?= h($f["MATRICULA"]) ?></td>
                                    <td><?= h($f["ALUMNO"]) ?></td>
                                    <td><?= h($f["CORREO"]) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
        <div class="card-footer text-center text-muted small">
            Reporte de alumnos por grupo - Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR', 'MAESTRO']);

$modo      = ($_GET["modo"] ?? "individual") === "masiva" ? "masiva" : "individual";
$idMateria = (int)($_GET["id_materia"] ?? $_POST["id_materia"] ?? 0);
$idUnidad  = (int)($_GET["id_unidad"] ?? $_POST["id_unidad"] ?? 0);
$idGrupo   = (int)($_GET["id_grupo"] ?? $_POST["id_grupo"] ?? 0);

$soloMaestro = esMaestro();
$idProfSesion = $soloMaestro ? (int)$_SESSION["id_profesor"] : 0;
$mensaje = "";

// Guardar Individual
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["guardar_individual"])) {
    $idAlum = (int)$_POST["id_alumno"];
    $calif  = (float)$_POST["calificacion"];
    if ($idMateria > 0 && $idUnidad > 0 && $idAlum > 0 && $calif >= 0 && $calif <= 10) {
        $stmt = $conexion->prepare(
            "INSERT INTO calificaciones (ID_ALUMNOS, ID_MATERIAS, CALIFICACION, ID_UNIDAD) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE CALIFICACION = VALUES(CALIFICACION)"
        );
        $stmt->bind_param("iidi", $idAlum, $idMateria, $calif, $idUnidad);
        $stmt->execute();
        $stmt->close();
        $mensaje = '<div class="alert alert-success">Calificación guardada correctamente.</div>';
    }
}

// Guardar Masiva
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["guardar_masiva"])) {
    $califs = $_POST["calif"] ?? [];
    $guardadas = 0;
    if ($idMateria > 0 && $idUnidad > 0) {
        $stmt = $conexion->prepare(
            "INSERT INTO calificaciones (ID_ALUMNOS, ID_MATERIAS, CALIFICACION, ID_UNIDAD) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE CALIFICACION = VALUES(CALIFICACION)"
        );
        foreach ($califs as $idAlum => $valor) {
            if ($valor !== "" && is_numeric($valor)) {
                $idAlumInt = (int)$idAlum;
                $nota = (float)$valor;
                if ($nota >= 0 && $nota <= 10) {
                    $stmt->bind_param("iidi", $idAlumInt, $idMateria, $nota, $idUnidad);
                    $stmt->execute();
                    $guardadas++;
                }
            }
        }
        $stmt->close();
        $mensaje = '<div class="alert alert-success">Se guardaron ' . $guardadas . ' calificación(es).</div>';
    }
}

// Catálogos para filtros
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

$unidades = $conexion->query("SELECT ID_UNIDAD, DESCRIPCION FROM unidad WHERE ESTATUS = 'ALTA' ORDER BY ID_UNIDAD")->fetch_all(MYSQLI_ASSOC);
$grupos   = $conexion->query("SELECT ID_GRUPOS, NOMBRE_GRUPO FROM grupos WHERE ESTATUS = 'ALTA' ORDER BY NOMBRE_GRUPO")->fetch_all(MYSQLI_ASSOC);

// Alumnos para modo Individual o Masiva
$alumnosLista = [];
if ($modo === "individual") {
    $sqlInd = "SELECT DISTINCT a.ID_ALUMNOS, a.MATRICULA, a.NOMBRE, a.APELLIDO_PATERNO, a.APELLIDO_MATERNO
               FROM alumnos a
               INNER JOIN asignatura s ON s.ID_ALUMNOS = a.ID_ALUMNOS
               WHERE a.ESTATUS = 'ALTA'" . ($soloMaestro ? " AND s.ID_PROFESORES = " . $idProfSesion : "") . "
               ORDER BY a.APELLIDO_PATERNO, a.NOMBRE";
    $alumnosLista = $conexion->query($sqlInd)->fetch_all(MYSQLI_ASSOC);
} elseif ($modo === "masiva" && $idMateria > 0 && $idUnidad > 0) {
    $sqlMas = "SELECT DISTINCT a.ID_ALUMNOS, a.MATRICULA, a.NOMBRE, a.APELLIDO_PATERNO, a.APELLIDO_MATERNO, c.CALIFICACION
               FROM asignatura s
               INNER JOIN alumnos a ON a.ID_ALUMNOS = s.ID_ALUMNOS
               LEFT JOIN grupo_alumno ga ON ga.ID_ALUMNOS = a.ID_ALUMNOS AND ga.ESTATUS = 'ALTA'
               LEFT JOIN calificaciones c ON c.ID_ALUMNOS = a.ID_ALUMNOS AND c.ID_MATERIAS = s.ID_MATERIAS AND c.ID_UNIDAD = ?
               WHERE s.ID_MATERIAS = ? AND a.ESTATUS = 'ALTA'";
    if ($soloMaestro) {
        $sqlMas .= " AND s.ID_PROFESORES = " . $idProfSesion;
    }
    if ($idGrupo > 0) {
        $sqlMas .= " AND ga.ID_GRUPOS = " . $idGrupo;
    }
    $sqlMas .= " ORDER BY a.APELLIDO_PATERNO, a.APELLIDO_MATERNO, a.NOMBRE";

    $stmt = $conexion->prepare($sqlMas);
    $stmt->bind_param("ii", $idUnidad, $idMateria);
    $stmt->execute();
    $alumnosLista = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Descarga directa a Excel desde PHP en modo Masiva
if ($modo === "masiva" && isset($_GET["descargar_excel"]) && $idMateria > 0 && $idUnidad > 0) {
    $nombreMat = "Materia";
    foreach ($materias as $mItem) {
        if ((int)$mItem["ID_MATERIAS"] === $idMateria) {
            $nombreMat = $mItem["CLAVE_MATERIA"] . " - " . $mItem["NOMBRE_MATERIA"];
            break;
        }
    }
    $nombreUni = "Unidad " . $idUnidad;
    foreach ($unidades as $uItem) {
        if ((int)$uItem["ID_UNIDAD"] === $idUnidad) {
            $nombreUni = $uItem["ID_UNIDAD"] . " - " . $uItem["DESCRIPCION"];
            break;
        }
    }

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"captura_calificaciones_u" . $idUnidad . ".xls\"");
    echo "\xEF\xBB\xBF";
    echo "<table border='1'>";
    echo "<tr><th colspan='4'>Captura de calificaciones — " . h($nombreMat) . " (" . h($nombreUni) . ")</th></tr>";
    echo "<tr><th>Id alumno</th><th>Matrícula</th><th>Alumno</th><th>Calificación registrada</th></tr>";
    foreach ($alumnosLista as $al) {
        $nomCompleto = $al["NOMBRE"] . " " . $al["APELLIDO_PATERNO"] . " " . $al["APELLIDO_MATERNO"];
        $calTexto = $al["CALIFICACION"] !== null ? $al["CALIFICACION"] : "";
        echo "<tr><td>" . (int)$al["ID_ALUMNOS"] . "</td><td>" . h($al["MATRICULA"]) . "</td><td>" . h($nomCompleto) . "</td><td>" . h($calTexto) . "</td></tr>";
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
    <title>Captura de Calificaciones - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-tabs .nav-link.active { font-weight: bold; color: #172554 !important; border-bottom: 3px solid #172554 !important; }
        .nav-tabs .nav-link { color: #555; }
    </style>
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Captura de calificaciones</h2>
        </div>
        <div class="card-body">
            <?= $mensaje ?>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?= $modo === 'individual' ? 'active' : '' ?>" href="captura_calificaciones.php?modo=individual">Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $modo === 'masiva' ? 'active' : '' ?>" href="captura_calificaciones.php?modo=masiva">Masiva</a>
                </li>
            </ul>

            <?php if ($modo === "individual") { ?>
                <form method="POST" class="row g-3">
                    <input type="hidden" name="guardar_individual" value="1">
                    <div class="col-md-4">
                        <label class="form-label">Materia</label>
                        <select name="id_materia" class="form-select" required>
                            <option value="">Selecciona materia...</option>
                            <?php foreach ($materias as $m) { ?>
                                <option value="<?= $m['ID_MATERIAS'] ?>" <?= $idMateria === (int)$m['ID_MATERIAS'] ? 'selected' : '' ?>>
                                    <?= h($m['CLAVE_MATERIA'] . " - " . $m['NOMBRE_MATERIA']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Unidad</label>
                        <select name="id_unidad" class="form-select" required>
                            <option value="">Selecciona unidad...</option>
                            <?php foreach ($unidades as $u) { ?>
                                <option value="<?= $u['ID_UNIDAD'] ?>" <?= $idUnidad === (int)$u['ID_UNIDAD'] ? 'selected' : '' ?>>
                                    <?= h($u['ID_UNIDAD'] . " - " . $u['DESCRIPCION']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Alumno</label>
                        <select name="id_alumno" class="form-select" required>
                            <option value="">Selecciona alumno...</option>
                            <?php foreach ($alumnosLista as $a) { ?>
                                <option value="<?= $a['ID_ALUMNOS'] ?>">
                                    <?= h($a['MATRICULA'] . " - " . $a['NOMBRE'] . " " . $a['APELLIDO_PATERNO']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Calificación</label>
                        <input type="number" step="0.1" min="0" max="10" name="calificacion" class="form-control" required placeholder="0.0">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-sae">Guardar calificación</button>
                    </div>
                </form>

            <?php } else { ?>
                <form method="GET" class="row g-3 mb-4">
                    <input type="hidden" name="modo" value="masiva">
                    <div class="col-md-4">
                        <label class="form-label">Materia</label>
                        <select name="id_materia" class="form-select" required>
                            <option value="">Selecciona materia...</option>
                            <?php foreach ($materias as $m) { ?>
                                <option value="<?= $m['ID_MATERIAS'] ?>" <?= $idMateria === (int)$m['ID_MATERIAS'] ? 'selected' : '' ?>>
                                    <?= h($m['CLAVE_MATERIA'] . " - " . $m['NOMBRE_MATERIA']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Unidad</label>
                        <select name="id_unidad" class="form-select" required>
                            <option value="">Selecciona unidad...</option>
                            <?php foreach ($unidades as $u) { ?>
                                <option value="<?= $u['ID_UNIDAD'] ?>" <?= $idUnidad === (int)$u['ID_UNIDAD'] ? 'selected' : '' ?>>
                                    <?= h($u['ID_UNIDAD'] . " - " . $u['DESCRIPCION']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Grupo (opcional)</label>
                        <select name="id_grupo" class="form-select">
                            <option value="0">Todos los grupos</option>
                            <?php foreach ($grupos as $g) { ?>
                                <option value="<?= $g['ID_GRUPOS'] ?>" <?= $idGrupo === (int)$g['ID_GRUPOS'] ? 'selected' : '' ?>>
                                    <?= h($g['NOMBRE_GRUPO']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">Cargar alumnos</button>
                    </div>
                </form>

                <?php if ($idMateria > 0 && $idUnidad > 0) { ?>
                    <form method="POST">
                        <input type="hidden" name="guardar_masiva" value="1">
                        <input type="hidden" name="id_materia" value="<?= $idMateria ?>">
                        <input type="hidden" name="id_unidad" value="<?= $idUnidad ?>">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Id alumno</th>
                                        <th>Matrícula</th>
                                        <th>Alumno</th>
                                        <th>Calificación (capturar)</th>
                                        <th>Calificación registrada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($alumnosLista) === 0) { ?>
                                        <tr><td colspan="5" class="text-center text-muted">No hay alumnos asignados a esta materia/grupo.</td></tr>
                                    <?php } ?>
                                    <?php foreach ($alumnosLista as $al) {
                                        $inputId = "nota_" . (int)$al['ID_ALUMNOS'];
                                    ?>
                                        <tr>
                                            <td><?= $al['ID_ALUMNOS'] ?></td>
                                            <td><?= h($al['MATRICULA']) ?></td>
                                            <td><?= h($al['NOMBRE'] . " " . $al['APELLIDO_PATERNO'] . " " . $al['APELLIDO_MATERNO']) ?></td>
                                            <td>
                                                <input id="<?= $inputId ?>" type="number" step="0.1" min="0" max="10" name="calif[<?= $al['ID_ALUMNOS'] ?>]" class="form-control form-control-sm" placeholder="Ej. 8.5">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm bg-light" readonly value="<?= $al['CALIFICACION'] !== null ? h($al['CALIFICACION']) : '—' ?>">
                                                <?php if ($al['CALIFICACION'] !== null) { ?>
                                                    <a href="#" class="small d-inline-block mt-1" onclick="var el=document.getElementById('<?= $inputId ?>'); el.value='<?= h($al['CALIFICACION']) ?>'; el.focus(); return false;">Corregir</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="submit" class="btn btn-sae">Guardar calificaciones</button>
                            <a href="<?= $base ?>/reportes/rep_calificaciones.php?id_materia=<?= $idMateria ?>&id_grupo=<?= $idGrupo ?>" class="btn btn-outline-success">
                                Enviar a Reportes
                            </a>
                            <a href="captura_calificaciones.php?modo=masiva&id_materia=<?= $idMateria ?>&id_unidad=<?= $idUnidad ?>&id_grupo=<?= $idGrupo ?>&descargar_excel=1" class="btn btn-success">
                                Descargar Excel
                            </a>
                        </div>
                        <div class="text-muted small mt-2">
                            Solo se guardan las filas que tengan calificación capturada. Guarda antes de enviar al reporte: el Excel incluye las calificaciones ya guardadas[cite: 22].
                        </div>
                    </form>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="card-footer text-center text-muted">Captura de calificaciones - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

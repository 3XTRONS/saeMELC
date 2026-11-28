<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$mensaje = "";

// Inscribir o actualizar estatuus
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["inscribir"])) {
    $idGrupo = (int)($_POST["id_grupo"] ?? 0);
    $idAlum  = (int)($_POST["id_alumno"] ?? 0);
    $estatus = isset($_POST["estatus_activo"]) ? "ALTA" : "BAJA";

    if ($idGrupo > 0 && $idAlum > 0) {
        $stmt = $conexion->prepare(
            "INSERT INTO grupo_alumno (ID_GRUPOS, ID_ALUMNOS, ESTATUS) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE ESTATUS = VALUES(ESTATUS)"
        );
        $stmt->bind_param("iis", $idGrupo, $idAlum, $estatus);
        $stmt->execute();
        $stmt->close();
        $mensaje = '<div class="alert alert-success">Inscripción guardada correctamente.</div>';
    }
}

// Dar de baja (cambia a BAJA sin eliminar lo de resgistro)
if (isset($_GET["baja_g"], $_GET["baja_a"])) {
    $g = (int)$_GET["baja_g"];
    $a = (int)$_GET["baja_a"];
    $stmt = $conexion->prepare("UPDATE grupo_alumno SET ESTATUS = 'BAJA' WHERE ID_GRUPOS = ? AND ID_ALUMNOS = ?");
    $stmt->bind_param("ii", $g, $a);
    $stmt->execute();
    $stmt->close();
    header("Location: inscripcion_grupo.php");
    exit;
}

// Reactivar (cambia de BAJA a ALTA)
if (isset($_GET["activar_g"], $_GET["activar_a"])) {
    $g = (int)$_GET["activar_g"];
    $a = (int)$_GET["activar_a"];
    $stmt = $conexion->prepare("UPDATE grupo_alumno SET ESTATUS = 'ALTA' WHERE ID_GRUPOS = ? AND ID_ALUMNOS = ?");
    $stmt->bind_param("ii", $g, $a);
    $stmt->execute();
    $stmt->close();
    header("Location: inscripcion_grupo.php");
    exit;
}

// Eliminar definitivamente de grupo_alumno
if (isset($_GET["elim_g"], $_GET["elim_a"])) {
    $g = (int)$_GET["elim_g"];
    $a = (int)$_GET["elim_a"];
    $stmt = $conexion->prepare("DELETE FROM grupo_alumno WHERE ID_GRUPOS = ? AND ID_ALUMNOS = ?");
    $stmt->bind_param("ii", $g, $a);
    $stmt->execute();
    $stmt->close();
    header("Location: inscripcion_grupo.php");
    exit;
}

$grupos  = $conexion->query("SELECT ID_GRUPOS, NOMBRE_GRUPO FROM grupos WHERE ESTATUS = 'ALTA' ORDER BY NOMBRE_GRUPO");
$alumnos = $conexion->query("SELECT ID_ALUMNOS, MATRICULA, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO FROM alumnos WHERE ESTATUS = 'ALTA' ORDER BY APELLIDO_PATERNO, NOMBRE");

$inscritos = $conexion->query(
    "SELECT ga.ID_GRUPOS, g.NOMBRE_GRUPO, ga.ID_ALUMNOS, a.MATRICULA,
            CONCAT(a.NOMBRE, ' ', a.APELLIDO_PATERNO, ' ', a.APELLIDO_MATERNO) AS alumno,
            ga.ESTATUS
     FROM grupo_alumno ga
     INNER JOIN grupos g ON g.ID_GRUPOS = ga.ID_GRUPOS
     INNER JOIN alumnos a ON a.ID_ALUMNOS = ga.ID_ALUMNOS
     WHERE g.ESTATUS = 'ALTA' AND a.ESTATUS = 'ALTA'
     ORDER BY ga.ID_GRUPOS ASC, a.APELLIDO_PATERNO ASC, a.NOMBRE ASC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de alumnos a grupos - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Inscripción de alumnos a grupos</h2>
        </div>

        <div class="card-body">
            <?= $mensaje ?>

            <form method="POST" action="inscripcion_grupo.php">
                <input type="hidden" name="inscribir" value="1">

                <div class="mb-3">
                    <label class="form-label">Id grupo</label>
                    <select name="id_grupo" class="form-select" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php while ($g = $grupos->fetch_assoc()) { ?>
                            <option value="<?= $g['ID_GRUPOS'] ?>"><?= h($g['NOMBRE_GRUPO']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Id alumno</label>
                    <select name="id_alumno" class="form-select" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php while ($a = $alumnos->fetch_assoc()) { ?>
                            <option value="<?= $a['ID_ALUMNOS'] ?>">
                                <?= h($a['MATRICULA'] . " - " . $a['NOMBRE'] . " " . $a['APELLIDO_PATERNO'] . " " . $a['APELLIDO_MATERNO']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="estatus_activo" id="estatusActivo" checked>
                    <label class="form-check-label" for="estatusActivo">Estatus activo</label>
                </div>

                <button type="submit" class="btn btn-sae">Inscribir alumno</button>
            </form>

            <hr class="my-4">

            <h4 class="mb-3">Alumnos inscritos</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Id grupo</th>
                            <th>Grupo</th>
                            <th>Id alumno</th>
                            <th>Matrícula</th>
                            <th>Alumno</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($inscritos->num_rows === 0) { ?>
                            <tr><td colspan="7" class="text-center text-muted">No hay alumnos inscritos en grupos.</td></tr>
                        <?php } ?>
                        <?php while ($f = $inscritos->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $f['ID_GRUPOS'] ?></td>
                                <td><?= h($f['NOMBRE_GRUPO']) ?></td>
                                <td><?= $f['ID_ALUMNOS'] ?></td>
                                <td><?= h($f['MATRICULA']) ?></td>
                                <td><?= h($f['alumno']) ?></td>
                                <td>
                                    <?php if ($f['ESTATUS'] === 'ALTA') { ?>
                                        <span class="badge bg-success">ALTA</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">BAJA</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($f['ESTATUS'] === 'ALTA') { ?>
                                        <a href="inscripcion_grupo.php?baja_g=<?= $f['ID_GRUPOS'] ?>&baja_a=<?= $f['ID_ALUMNOS'] ?>"
                                           class="btn btn-outline-secondary btn-sm"
                                           title="Dar de baja">❎</a>
                                    <?php } else { ?>
                                        <a href="inscripcion_grupo.php?activar_g=<?= $f['ID_GRUPOS'] ?>&activar_a=<?= $f['ID_ALUMNOS'] ?>"
                                           class="btn btn-outline-success btn-sm"
                                           title="Reactivar">🔄</a>
                                    <?php } ?>
                                    <a href="inscripcion_grupo.php?elim_g=<?= $f['ID_GRUPOS'] ?>&elim_a=<?= $f['ID_ALUMNOS'] ?>"
                                       class="btn btn-outline-danger btn-sm"
                                       title="Eliminar"
                                       onclick="return confirm('¿Eliminar este registro definitivamente?');">🗑️</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer text-center text-muted">
            Inscripción a grupos - Programación II Lopez Cornejo Martin Emmanuel
        </div>
    </div>
</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

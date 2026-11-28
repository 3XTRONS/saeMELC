<?php
include_once("../conexion.php");
requerirRol(['ADMINISTRADOR']);

$mensaje = "";
$editId = (int)($_GET["editar"] ?? $_POST["id_asignatura"] ?? 0);
$editarFila = null;

if ($editId > 0 && $_SERVER["REQUEST_METHOD"] !== "POST") {
    $stmt = $conexion->prepare("SELECT * FROM asignatura WHERE ID_ASIGNATURA = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editarFila = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    if (!$editarFila) {
        $editId = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["asignar"])) {
    $idMat  = (int)($_POST["id_materia"] ?? 0);
    $idAlum = (int)($_POST["id_alumno"] ?? 0);
    $idProf = (int)($_POST["id_profesor"] ?? 0);
    $idAsigPost = (int)($_POST["id_asignatura"] ?? 0);

    if ($idMat > 0 && $idAlum > 0 && $idProf > 0) {
        if ($idAsigPost > 0) {
            $stmt = $conexion->prepare(
                "UPDATE asignatura SET ID_MATERIAS = ?, ID_ALUMNOS = ?, ID_PROFESORES = ? WHERE ID_ASIGNATURA = ?"
            );
            $stmt->bind_param("iiii", $idMat, $idAlum, $idProf, $idAsigPost);
            $stmt->execute();
            $stmt->close();
            $mensaje = '<div class="alert alert-success">Asignación actualizada correctamente.</div>';
            $editId = 0;
            $editarFila = null;
        } else {
            $stmt = $conexion->prepare(
                "INSERT INTO asignatura (ID_MATERIAS, ID_ALUMNOS, ID_PROFESORES) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE ID_PROFESORES = VALUES(ID_PROFESORES)"
            );
            $stmt->bind_param("iii", $idMat, $idAlum, $idProf);
            $stmt->execute();
            $stmt->close();
            $mensaje = '<div class="alert alert-success">Asignación guardada correctamente.</div>';
        }
    }
}

if (isset($_GET["eliminar"])) {
    $idAsig = (int)$_GET["eliminar"];
    $stmt = $conexion->prepare("DELETE FROM asignatura WHERE ID_ASIGNATURA = ?");
    $stmt->bind_param("i", $idAsig);
    $stmt->execute();
    $stmt->close();
    header("Location: asignacion_materia.php");
    exit;
}

$materias = $conexion->query("SELECT * FROM materias WHERE ESTATUS = 'ALTA' ORDER BY CLAVE_MATERIA")->fetch_all(MYSQLI_ASSOC);
$alumnos  = $conexion->query("SELECT * FROM alumnos WHERE ESTATUS = 'ALTA' ORDER BY APELLIDO_PATERNO, NOMBRE")->fetch_all(MYSQLI_ASSOC);
$profes   = $conexion->query("SELECT * FROM profesores WHERE ESTATUS = 'ALTA' ORDER BY APELLIDO_PATERNO, NOMBRE")->fetch_all(MYSQLI_ASSOC);

$lista = $conexion->query(
    "SELECT s.ID_ASIGNATURA, m.CLAVE_MATERIA, m.NOMBRE_MATERIA,
            CONCAT(a.MATRICULA, ' - ', a.NOMBRE, ' ', a.APELLIDO_PATERNO) AS alumno,
            CONCAT(p.NOMBRE, ' ', p.APELLIDO_PATERNO) AS profesor
     FROM asignatura s
     INNER JOIN materias m ON m.ID_MATERIAS = s.ID_MATERIAS
     INNER JOIN alumnos a ON a.ID_ALUMNOS = s.ID_ALUMNOS
     INNER JOIN profesores p ON p.ID_PROFESORES = s.ID_PROFESORES
     ORDER BY m.CLAVE_MATERIA, a.APELLIDO_PATERNO"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Materias - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("../navegacion/nav.php"); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-sae-zafiro text-center">
            <h2 class="mb-0">Asignación de Materias</h2>
        </div>
        <div class="card-body">
            <?= $mensaje ?>

            <form method="POST" action="asignacion_materia.php" class="row g-3 mb-4">
                <input type="hidden" name="asignar" value="1">
                <?php if ($editarFila !== null) { ?>
                    <input type="hidden" name="id_asignatura" value="<?= (int)$editarFila['ID_ASIGNATURA'] ?>">
                <?php } ?>

                <div class="col-md-4">
                    <label class="form-label">Materia</label>
                    <select name="id_materia" class="form-select" required>
                        <option value="">Selecciona materia...</option>
                        <?php foreach ($materias as $m) {
                            $sel = ($editarFila !== null && (int)$editarFila['ID_MATERIAS'] === (int)$m['ID_MATERIAS']) ? 'selected' : '';
                        ?>
                            <option value="<?= $m['ID_MATERIAS'] ?>" <?= $sel ?>><?= h($m['CLAVE_MATERIA'] . " - " . $m['NOMBRE_MATERIA']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Alumno</label>
                    <select name="id_alumno" class="form-select" required>
                        <option value="">Selecciona alumno...</option>
                        <?php foreach ($alumnos as $a) {
                            $sel = ($editarFila !== null && (int)$editarFila['ID_ALUMNOS'] === (int)$a['ID_ALUMNOS']) ? 'selected' : '';
                        ?>
                            <option value="<?= $a['ID_ALUMNOS'] ?>" <?= $sel ?>><?= h($a['MATRICULA'] . " - " . $a['NOMBRE'] . " " . $a['APELLIDO_PATERNO']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profesor</label>
                    <select name="id_profesor" class="form-select" required>
                        <option value="">Selecciona profesor...</option>
                        <?php foreach ($profes as $p) {
                            $sel = ($editarFila !== null && (int)$editarFila['ID_PROFESORES'] === (int)$p['ID_PROFESORES']) ? 'selected' : '';
                        ?>
                            <option value="<?= $p['ID_PROFESORES'] ?>" <?= $sel ?>><?= h($p['NOMBRE'] . " " . $p['APELLIDO_PATERNO']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sae">
                        <?= $editarFila !== null ? "Actualizar asignación" : "Asignar Materia" ?>
                    </button>
                    <?php if ($editarFila !== null) { ?>
                        <a href="asignacion_materia.php" class="btn btn-outline-secondary">Cancelar edición</a>
                    <?php } ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr><th>Clave</th><th>Materia</th><th>Alumno</th><th>Profesor</th><th class="text-center">Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($f = $lista->fetch_assoc()) { ?>
                        <tr>
                            <td><?= h($f['CLAVE_MATERIA']) ?></td>
                            <td><?= h($f['NOMBRE_MATERIA']) ?></td>
                            <td><?= h($f['alumno']) ?></td>
                            <td><?= h($f['profesor']) ?></td>
                            <td class="text-center">
                                <a href="asignacion_materia.php?editar=<?= $f['ID_ASIGNATURA'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="asignacion_materia.php?eliminar=<?= $f['ID_ASIGNATURA'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta asignación?');">Quitar</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center text-muted">Asignación de Materias - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

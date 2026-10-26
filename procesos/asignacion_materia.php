<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

// 1. Eliminar registro al dar clic en 🗑️
if (isset($_GET['eliminar'])) {
    $idasignatura = intval($_GET['eliminar']);
    $stmt_del = $conexion->prepare("DELETE FROM asignaciones WHERE idasignatura = ?");
    if ($stmt_del) {
        $stmt_del->bind_param("i", $idasignatura);
        if ($stmt_del->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>Asignación eliminada correctamente.</div>";
        }
        $stmt_del->close();
    }
}

// 2. Registrar nueva asignación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idmat   = $_POST['idmat'] ?? '';
    $idalumn = $_POST['idalumn'] ?? '';
    $idprof  = $_POST['idprof'] ?? '';

    if (!empty($idmat) && !empty($idalumn) && !empty($idprof)) {
        $stmt = $conexion->prepare("INSERT INTO asignaciones (idmat, idalumn, idprof) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $idmat, $idalumn, $idprof);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>¡Materia asignada correctamente!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>Error al asignar: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

$materias   = $conexion->query("SELECT idmat, clave_mat, nombre_mat FROM materias WHERE estatus_mat = 'ALTA'");
$alumnos    = $conexion->query("SELECT idalumn, matricula_al, nombre_al, apaterno_al, amaterno_al FROM alumnos WHERE estatus_al = 'ALTA'");
$profesores = $conexion->query("SELECT idprof, nombre_prof, apaterno_prof, amaterno_prof FROM profesores WHERE estatus_prof = 'ALTA'");

$asignaturas = $conexion->query("SELECT asig.idasignatura, m.nombre_mat AS materia, CONCAT(a.nombre_al, ' ', a.apaterno_al) AS alumno, CONCAT(p.nombre_prof, ' ', p.apaterno_prof) AS profesor FROM asignaciones asig JOIN materias m ON asig.idmat = m.idmat JOIN alumnos a ON asig.idalumn = a.idalumn JOIN profesores p ON asig.idprof = p.idprof");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de materias</title>
    <style>
        body { background-color: #f8f9fa; }
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .form-header {
            background-color: #1a2b4c;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .form-body { padding: 30px; }
        .btn-custom {
            background-color: #800020;
            color: white;
        }
        .btn-custom:hover { background-color: #600018; color: white; }
        .footer-text {
            text-align: center;
            padding: 15px;
            font-size: 0.9em;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .btn-emoji {
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #bb2d3b; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h2>Asignación de materias</h2>
        </div>
        
        <div class="form-body">
            <?php echo $mensaje; ?>

            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="idmat" class="font-weight-bold">Id materia</label>
                    <select name="idmat" id="idmat" class="form-control" required>
                        <option value="">Selecciona una materia...</option>
                        <?php while ($row = $materias->fetch_assoc()): ?>
                            <option value="<?php echo $row['idmat']; ?>">
                                <?php echo $row['clave_mat'] . " - " . $row['nombre_mat']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="idalumn" class="font-weight-bold">Id alumno</label>
                    <select name="idalumn" id="idalumn" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php while ($row = $alumnos->fetch_assoc()): ?>
                            <option value="<?php echo $row['idalumn']; ?>">
                                <?php echo $row['matricula_al'] . " - " . $row['nombre_al'] . " " . $row['apaterno_al']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="idprof" class="font-weight-bold">Id profesor</label>
                    <select name="idprof" id="idprof" class="form-control" required>
                        <option value="">Selecciona un profesor...</option>
                        <?php while ($row = $profesores->fetch_assoc()): ?>
                            <option value="<?php echo $row['idprof']; ?>">
                                <?php echo $row['nombre_prof'] . " " . $row['apaterno_prof']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-custom">Asignar materia</button>
            </form>
        </div>

        <div class="footer-text">
            Asignación de materias - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>

    <!-- TABLA DE ASIGNATURAS REGISTRADAS -->
    <div class="container mt-4 mb-5" style="max-width: 800px;">
        <h3 class="mb-3">Asignaturas registradas</h3>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Id asignatura</th>
                    <th>Materia</th>
                    <th>Alumno</th>
                    <th>Profesor</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($asignaturas->num_rows > 0): ?>
                    <?php while ($row = $asignaturas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['idasignatura']; ?></td>
                            <td><?php echo $row['materia']; ?></td>
                            <td><?php echo $row['alumno']; ?></td>
                            <td><?php echo $row['profesor']; ?></td>
                            <td class="text-center">
                                <a href="asignacion_materia.php?eliminar=<?php echo $row['idasignatura']; ?>" class="btn-emoji btn-delete" onclick="return confirm('¿Eliminar asignación?');" title="Eliminar de BD">🗑️</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay asignaturas registradas aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

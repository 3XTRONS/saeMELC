<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

// Procesar el registro de la asignación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idmat   = intval($_POST['idmat'] ?? 0);
    $idalumn = intval($_POST['idalumn'] ?? 0);
    $idprof  = intval($_POST['idprof'] ?? 0);

    if ($idmat > 0 && $idalumn > 0 && $idprof > 0) {
        $stmt = $conexion->prepare("INSERT INTO asignaciones (idmat, idalumn, idprof) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iii", $idmat, $idalumn, $idprof);
            if ($stmt->execute()) {
                $mensaje = '<div class="alert alert-success mt-3">¡Materia asignada correctamente!</div>';
            } else {
                $mensaje = '<div class="alert alert-danger mt-3">Error al asignar: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        } else {
            $mensaje = '<div class="alert alert-danger mt-3">Error en la base de datos: ' . htmlspecialchars($conexion->error) . '</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning mt-3">Por favor selecciona la materia, el alumno y el profesor.</div>';
    }
}

// Consultas para los selectores usando los nombres exactos de saeMELC.sql
$materias = $conexion->query("SELECT idmat, clave_mat, nombre_mat FROM materias WHERE estatus_mat = 'ALTA'");
$alumnos  = $conexion->query("SELECT idalumn, matricula_al, nombre_al, apaterno_al FROM alumnos WHERE estatus_al = 'ALTA'");
$profes   = $conexion->query("SELECT idprof, nombre_prof, apaterno_prof FROM profesores WHERE estatus_prof = 'ALTA'");

// Consulta para listar las asignaciones registradas
$sql_asig = "SELECT asig.idasignatura, m.nombre_mat, 
                    CONCAT(a.nombre_al, ' ', a.apaterno_al) AS alumno,
                    CONCAT(p.nombre_prof, ' ', p.apaterno_prof) AS profesor
             FROM asignaciones asig
             JOIN materias m ON asig.idmat = m.idmat
             JOIN alumnos a ON asig.idalumn = a.idalumn
             JOIN profesores p ON asig.idprof = p.idprof
             ORDER BY asig.idasignatura DESC";
$asignaciones = $conexion->query($sql_asig);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 1000px;
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
        .titulo h2 { margin: 0; font-size: 1.8rem; font-weight: 500; }
        .contenido { padding: 25px; }
        .btn-sae {
            background-color: #9f1239;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie {
            text-align: center;
            padding: 15px;
            color: #666;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- FORMULARIO DE ASIGNACIÓN -->
    <div class="formulario">
        <div class="titulo">
            <h2>Asignación de materias</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <form method="post" action="asignacion_materia.php">
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Id materia</label>
                    <select name="idmat" class="form-control" required>
                        <option value="">Selecciona una materia...</option>
                        <?php if ($materias): ?>
                            <?php while($m = $materias->fetch_assoc()): ?>
                                <option value="<?php echo $m['idmat']; ?>">
                                    <?php echo htmlspecialchars($m['clave_mat'] . ' - ' . $m['nombre_mat']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Id alumno</label>
                    <select name="idalumn" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php if ($alumnos): ?>
                            <?php while($a = $alumnos->fetch_assoc()): ?>
                                <option value="<?php echo $a['idalumn']; ?>">
                                    <?php echo htmlspecialchars($a['matricula_al'] . ' - ' . $a['nombre_al'] . ' ' . $a['apaterno_al']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Id profesor</label>
                    <select name="idprof" class="form-control" required>
                        <option value="">Selecciona un profesor...</option>
                        <?php if ($profes): ?>
                            <?php while($p = $profes->fetch_assoc()): ?>
                                <option value="<?php echo $p['idprof']; ?>">
                                    <?php echo htmlspecialchars($p['nombre_prof'] . ' ' . $p['apaterno_prof']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-sae mt-2">Asignar materia</button>
            </form>
        </div>

        <div class="pie">
            Asignación de materias - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>

    <!-- TABLA DE ASIGNATURAS REGISTRADAS -->
    <div class="formulario mt-4" style="box-shadow: none;">
        <div class="contenido p-0">
            <h4 class="p-3 mb-0">Asignaturas registradas</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead style="background-color: #212529; color: white;">
                        <tr>
                            <th>Id asignatura</th>
                            <th>Materia</th>
                            <th>Alumno</th>
                            <th>Profesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($asignaciones && $asignaciones->num_rows > 0): ?>
                            <?php while($row = $asignaciones->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['idasignatura']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_mat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['profesor']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No hay asignaciones registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>

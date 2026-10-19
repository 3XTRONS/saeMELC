<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

// Procesar la inscripción al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idgrupo  = intval($_POST['idgrupo'] ?? 0);
    $idalumn  = intval($_POST['idalumn'] ?? 0);
    $estatus  = isset($_POST['estatus']) ? 'ALTA' : 'INACTIVO';

    if ($idgrupo > 0 && $idalumn > 0) {
        $stmt = $conexion->prepare("INSERT INTO grupo_alumnos (idgrupo, idalumn, estatus) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iis", $idgrupo, $idalumn, $estatus);
            if ($stmt->execute()) {
                $mensaje = '<div class="alert alert-success mt-3">¡Alumno inscrito correctamente!</div>';
            } else {
                $mensaje = '<div class="alert alert-danger mt-3">Error al inscribir: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        } else {
            $mensaje = '<div class="alert alert-danger mt-3">Error en la base de datos: ' . htmlspecialchars($conexion->error) . '</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning mt-3">Por favor selecciona un grupo y un alumno.</div>';
    }
}

// Consultas adaptadas a saeMELC.sql
$grupos  = $conexion->query("SELECT idgrupo, clave_grupo, nombre_grupo FROM grupos WHERE estatus_grupo = 'ALTA'");
$alumnos = $conexion->query("SELECT idalumn, matricula_al, nombre_al, apaterno_al, amaterno_al FROM alumnos WHERE estatus_al = 'ALTA'");

// Consultar lista de alumnos inscritos para la tabla
$sql_tabla = "SELECT ga.idgrupo, g.clave_grupo, ga.idalumn, a.matricula_al, 
                     CONCAT(a.nombre_al, ' ', a.apaterno_al, ' ', a.amaterno_al) AS alumno, ga.estatus
              FROM grupo_alumnos ga
              JOIN grupos g ON ga.idgrupo = g.idgrupo
              JOIN alumnos a ON ga.idalumn = a.idalumn
              ORDER BY ga.idgrupo ASC";
$inscritos = $conexion->query($sql_tabla);
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
    <!-- TARJETA CON ESTILO AZUL MARINO Y GUINDA -->
    <div class="formulario">
        <div class="titulo">
            <h2>Inscripción de alumnos a grupos</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <form method="post" action="inscripcion_grupo.php">
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Id grupo</label>
                    <select name="idgrupo" class="form-control" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php if ($grupos): ?>
                            <?php while($g = $grupos->fetch_assoc()): ?>
                                <option value="<?php echo $g['idgrupo']; ?>">
                                    <?php echo htmlspecialchars($g['clave_grupo'] . ' - ' . $g['nombre_grupo']); ?>
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
                                    <?php echo htmlspecialchars($a['matricula_al'] . ' - ' . $a['nombre_al'] . ' ' . $a['apaterno_al'] . ' ' . $a['amaterno_al']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="estatus" id="estatus" checked>
                    <label class="form-check-label" for="estatus">Estatus activo</label>
                </div>

                <button type="submit" class="btn btn-sae">Inscribir alumno</button>
            </form>
        </div>

        <div class="pie">
            Inscripción de alumnos - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>

    <!-- TABLA DE ALUMNOS INSCRITOS -->
    <div class="formulario mt-4" style="box-shadow: none;">
        <div class="contenido p-0">
            <h4 class="p-3 mb-0">Alumnos inscritos</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead style="background-color: #212529; color: white;">
                        <tr>
                            <th>Id grupo</th>
                            <th>Grupo</th>
                            <th>Id alumno</th>
                            <th>Matrícula</th>
                            <th>Alumno</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($inscritos && $inscritos->num_rows > 0): ?>
                            <?php while($row = $inscritos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['idgrupo']; ?></td>
                                    <td><?php echo htmlspecialchars($row['clave_grupo']); ?></td>
                                    <td><?php echo $row['idalumn']; ?></td>
                                    <td><?php echo htmlspecialchars($row['matricula_al']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                    <td><span class="badge bg-success"><?php echo htmlspecialchars($row['estatus']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No hay alumnos inscritos registrados.</td>
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

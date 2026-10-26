<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";

// 1. Cambiar estatus (ALTA <-> BAJA) al dar clic en 🛠️
if (isset($_GET['cambiar_estatus'])) {
    $id_ga = intval($_GET['cambiar_estatus']);
    $estatus_actual = $_GET['estatus'] ?? 'ALTA';
    $nuevo_estatus = ($estatus_actual === 'ALTA') ? 'BAJA' : 'ALTA';

    $stmt_est = $conexion->prepare("UPDATE grupo_alumnos SET estatus = ? WHERE id_ga = ?");
    if ($stmt_est) {
        $stmt_est->bind_param("si", $nuevo_estatus, $id_ga);
        if ($stmt_est->execute()) {
            $mensaje = "<div class='alert alert-info mt-3'>Estatus actualizado a <b>$nuevo_estatus</b>.</div>";
        }
        $stmt_est->close();
    }
}

// 2. Eliminar registro al dar clic en 🗑️
if (isset($_GET['eliminar'])) {
    $id_ga = intval($_GET['eliminar']);
    $stmt_del = $conexion->prepare("DELETE FROM grupo_alumnos WHERE id_ga = ?");
    if ($stmt_del) {
        $stmt_del->bind_param("i", $id_ga);
        if ($stmt_del->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>Inscripción eliminada correctamente.</div>";
        }
        $stmt_del->close();
    }
}

// 3. Registrar nueva inscripción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idgrupo = $_POST['idgrupo'] ?? '';
    $idalumn = $_POST['idalumn'] ?? '';
    $estatus = isset($_POST['estatus']) ? 'ALTA' : 'BAJA';

    if (!empty($idgrupo) && !empty($idalumn)) {
        $stmt = $conexion->prepare("INSERT INTO grupo_alumnos (idgrupo, idalumn, estatus) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $idgrupo, $idalumn, $estatus);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>¡Alumno inscrito al grupo correctamente!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>Error al inscribir: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

$grupos = $conexion->query("SELECT idgrupo, clave_grupo, nombre_grupo FROM grupos WHERE estatus_grupo = 'ALTA'");
$alumnos = $conexion->query("SELECT idalumn, matricula_al, nombre_al, apaterno_al, amaterno_al FROM alumnos WHERE estatus_al = 'ALTA'");
$inscritos = $conexion->query("SELECT ga.id_ga, ga.idgrupo, g.clave_grupo, ga.idalumn, a.matricula_al, CONCAT(a.nombre_al, ' ', a.apaterno_al, ' ', a.amaterno_al) AS alumno, ga.estatus FROM grupo_alumnos ga JOIN grupos g ON ga.idgrupo = g.idgrupo JOIN alumnos a ON ga.idalumn = a.idalumn");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripción de alumnos a grupos</title>
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
        .btn-edit { background-color: #0d6efd; color: white; }
        .btn-edit:hover { background-color: #0b5ed7; color: white; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #bb2d3b; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <div class="form-header">
            <h2>Inscripción de alumnos a grupos</h2>
        </div>
        
        <div class="form-body">
            <?php echo $mensaje; ?>

            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="idgrupo" class="font-weight-bold">Id grupo</label>
                    <select name="idgrupo" id="idgrupo" class="form-control" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php while ($row = $grupos->fetch_assoc()): ?>
                            <option value="<?php echo $row['idgrupo']; ?>">
                                <?php echo $row['clave_grupo'] . " - " . $row['nombre_grupo']; ?>
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

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="estatus" id="estatus" checked>
                    <label class="form-check-label" for="estatus">
                        Estatus activo
                    </label>
                </div>

                <button type="submit" class="btn btn-custom">Inscribir alumno</button>
            </form>
        </div>

        <div class="footer-text">
            Inscripción de alumnos - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>

    <!-- TABLA DE ALUMNOS INSCRITOS -->
    <div class="container mt-4 mb-5" style="max-width: 800px;">
        <h3 class="mb-3">Alumnos inscritos</h3>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Id grupo</th>
                    <th>Grupo</th>
                    <th>Id alumno</th>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Estatus</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($inscritos->num_rows > 0): ?>
                    <?php while ($row = $inscritos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['idgrupo']; ?></td>
                            <td><?php echo $row['clave_grupo']; ?></td>
                            <td><?php echo $row['idalumn']; ?></td>
                            <td><?php echo $row['matricula_al']; ?></td>
                            <td><?php echo $row['alumno']; ?></td>
                            <td>
                                <span class="badge <?php echo ($row['estatus'] == 'ALTA') ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $row['estatus']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="inscripcion_grupo.php?cambiar_estatus=<?php echo $row['id_ga']; ?>&estatus=<?php echo $row['estatus']; ?>" class="btn-emoji btn-edit" title="Cambiar Estatus (ALTA/BAJA)">🛠️</a>
                                <a href="inscripcion_grupo.php?eliminar=<?php echo $row['id_ga']; ?>" class="btn-emoji btn-delete" onclick="return confirm('¿Eliminar inscripción?');" title="Eliminar de BD">🗑️</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay alumnos inscritos aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

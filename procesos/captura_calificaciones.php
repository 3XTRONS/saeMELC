<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";
$tab = $_GET['tab'] ?? 'individual';
$id_unidad_selected = intval($_GET['id_unidad'] ?? 0);

// Procesar guardado individual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_individual'])) {
    $idalumn      = intval($_POST['idalumn'] ?? 0);
    $id_unidad    = intval($_POST['id_unidad'] ?? 0);
    $calificacion = floatval($_POST['calificacion'] ?? 0);

    if ($idalumn > 0 && $id_unidad > 0) {
        $stmt = $conexion->prepare("INSERT INTO calificaciones (idalumn, id_unidad, calificacion) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iid", $idalumn, $id_unidad, $calificacion);
            if ($stmt->execute()) {
                $mensaje = '<div class="alert alert-success mt-3">¡Calificación registrada correctamente!</div>';
            } else {
                $mensaje = '<div class="alert alert-danger mt-3">Error al registrar: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        }
    }
}

// Procesar guardado masivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_masivo'])) {
    $id_unidad = intval($_POST['id_unidad'] ?? 0);
    $califs    = $_POST['calif'] ?? [];

    if ($id_unidad > 0 && !empty($califs)) {
        $exitos = 0;
        foreach ($califs as $id_al => $val_calif) {
            if ($val_calif !== '') {
                $val = floatval($val_calif);
                $stmt = $conexion->prepare("INSERT INTO calificaciones (idalumn, id_unidad, calificacion) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("iid", $id_al, $id_unidad, $val);
                    if ($stmt->execute()) $exitos++;
                    $stmt->close();
                }
            }
        }
        $mensaje = '<div class="alert alert-success mt-3">¡Se registraron ' . $exitos . ' calificaciones masivas!</div>';
    }
}

// Consultas
$alumnos = $conexion->query("SELECT idalumn, matricula_al, nombre_al, apaterno_al, amaterno_al FROM alumnos WHERE estatus_al = 'ALTA'");

$alumnos_masivos = null;
if ($id_unidad_selected > 0) {
    $alumnos_masivos = $conexion->query("SELECT a.idalumn, a.matricula_al, 
                                                CONCAT(a.nombre_al, ' ', a.apaterno_al, ' ', a.amaterno_al) AS alumno,
                                                (SELECT c.calificacion FROM calificaciones c WHERE c.idalumn = a.idalumn AND c.id_unidad = $id_unidad_selected ORDER BY c.id_calif DESC LIMIT 1) as registrada
                                         FROM alumnos a WHERE a.estatus_al = 'ALTA'");
}
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
        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: #172554 !important;
            border-bottom: 3px solid #172554 !important;
        }
        .nav-tabs .nav-link { color: #555; }
    </style>
</head>
<body>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2>Captura de calificaciones</h2>
        </div>

        <div class="contenido">
            <!-- PESTAÑAS INDIVIDUAL / MASIVA -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($tab == 'individual') ? 'active' : ''; ?>" href="captura_calificaciones.php?tab=individual">Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($tab == 'masiva') ? 'active' : ''; ?>" href="captura_calificaciones.php?tab=masiva">Masiva</a>
                </li>
            </ul>

            <?php echo $mensaje; ?>

            <!-- VISTA INDIVIDUAL -->
            <?php if ($tab == 'individual'): ?>
                <form method="post" action="captura_calificaciones.php?tab=individual">
                    <input type="hidden" name="guardar_individual" value="1">
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-3">
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Id unidad</label>
                            <select name="id_unidad" class="form-control" required>
                                <option value="">Selecciona una unidad...</option>
                                <option value="1">1 - Unidad 1 - Parcial 1</option>
                                <option value="2">2 - Unidad 2 - Parcial 2</option>
                                <option value="10">3 - Unidad 3 - Proyecto final</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Calificación</label>
                            <input type="number" step="0.1" min="0" max="10" name="calificacion" class="form-control" required placeholder="0.0">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-sae">Guardar Calificación</button>
                    </div>
                </form>

            <!-- VISTA MASIVA -->
            <?php else: ?>
                <form method="get" action="captura_calificaciones.php">
                    <input type="hidden" name="tab" value="masiva">
                    <div class="row align-items-end mb-4">
                        <div class="col-md-9 mb-2">
                            <label class="form-label font-weight-bold">Id unidad</label>
                            <select name="id_unidad" class="form-control" required>
                                <option value="">Selecciona una unidad...</option>
                                <option value="1" <?php echo ($id_unidad_selected == 1) ? 'selected' : ''; ?>>1 - Unidad 1 - Parcial 1</option>
                                <option value="2" <?php echo ($id_unidad_selected == 2) ? 'selected' : ''; ?>>2 - Unidad 2 - Parcial 2</option>
                                <option value="10" <?php echo ($id_unidad_selected == 10) ? 'selected' : ''; ?>>3 - Unidad 3 - Proyecto final</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-secondary w-100">Cargar alumnos</button>
                        </div>
                    </div>
                </form>

                <?php if ($id_unidad_selected > 0 && $alumnos_masivos): ?>
                    <form method="post" action="captura_calificaciones.php?tab=masiva&id_unidad=<?php echo $id_unidad_selected; ?>">
                        <input type="hidden" name="guardar_masivo" value="1">
                        <input type="hidden" name="id_unidad" value="<?php echo $id_unidad_selected; ?>">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead style="background-color: #212529; color: white;">
                                    <tr>
                                        <th>Id alumno</th>
                                        <th>Matrícula</th>
                                        <th>Alumno</th>
                                        <th>Calificación (capturar)</th>
                                        <th>Calificación registrada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $alumnos_masivos->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['idalumn']; ?></td>
                                            <td><?php echo htmlspecialchars($row['matricula_al']); ?></td>
                                            <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                            <td>
                                                <input type="number" step="0.1" min="0" max="10" name="calif[<?php echo $row['idalumn']; ?>]" class="form-control form-control-sm" placeholder="Ej. 8.5">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm bg-light" readonly value="<?php echo ($row['registrada'] !== null) ? $row['registrada'] : '-'; ?>">
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-sae mt-2">Guardar Calificaciones</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="pie">
            Captura de calificaciones - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

</body>
</html>

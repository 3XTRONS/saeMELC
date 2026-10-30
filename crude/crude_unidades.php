<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$sql = "SELECT id_unidad, nombre_unidad FROM unidades WHERE estatus_unidad = 'ALTA'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Unidades</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 900px;
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
            text-decoration: none;
            display: inline-block;
        }
        .btn-sae:hover { background-color: #881337; color: white; text-decoration: none; }
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
    <div class="formulario">
        <div class="titulo">
            <h2>Catálogo de Unidades</h2>
        </div>

        <div class="contenido">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="../registro/reg_unidad.php" class="btn-sae">
                    📌 Registrar nueva unidad
                </a>
                <a href="../registro/reg_unidad.php" class="btn btn-outline-secondary btn-sm">
                    ← Regresar al registro
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle m-0">
                    <thead style="background-color: #212529; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Nombre de la Unidad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php $i = 1; ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><b><?php echo $i++; ?></b></td>
                                    <td><?php echo htmlspecialchars($row['nombre_unidad']); ?></td>
                                    <td class="text-center">
                                        <a href="../modificacion/modif_unidad.php?id=<?php echo $row['id_unidad']; ?>" class="btn btn-primary btn-sm" title="Editar">✏️</a>
                                        <a href="../baja/baja_unidad.php?id=<?php echo $row['id_unidad']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas dar de baja esta unidad?');" title="Dar de baja">🗑️</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No hay unidades registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pie">
            Catálogo de unidades - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

</body>
</html>

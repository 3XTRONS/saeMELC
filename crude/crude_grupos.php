<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$grupos = $conexion->query("SELECT * FROM grupos WHERE estatus_grupo = 'ALTA'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Grupos</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario {
            background-color: white;
            max-width: 900px;
            margin: 30px auto;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
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
            <h2>Catálogo de Grupos</h2>
        </div>

        <div class="contenido">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="../registro/reg_grupo.php" class="text-dark font-weight-bold">👥 <u>Registrar nuevo grupo</u></a>
                <a href="../registro/reg_grupo.php" class="btn btn-outline-secondary btn-sm">← Regresar al registro</a>
            </div>

            <table class="table table-striped table-hover align-middle">
                <thead style="background-color: #212529; color: white;">
                    <tr>
                        <th>#</th>
                        <th>Clave del Grupo</th>
                        <th>Nombre del Grupo / Semestre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($grupos && $grupos->num_rows > 0): ?>
                        <?php $i = 1; ?>
                        <?php while ($row = $grupos->fetch_assoc()): ?>
                            <tr>
                                <td><b><?php echo $i++; ?></b></td>
                                <td><?php echo htmlspecialchars($row['clave_grupo']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_grupo']); ?></td>
                                <td class="text-center">
                                    <a href="../modificacion/modif_grupo.php?id=<?php echo $row['idgrupo']; ?>" class="btn btn-primary btn-sm">✏️</a>
                                    <a href="../baja/baja_grupo.php?id=<?php echo $row['idgrupo']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja grupo?');">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-3">No hay grupos activos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pie">
            Catálogo de grupos - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

</body>
</html>

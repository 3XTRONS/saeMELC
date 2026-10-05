<?php
include_once("../conexion.php");

$sql = "SELECT * FROM grupos WHERE estatus_grupo = 'ALTA'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Grupos - SAE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f3f1f4; }
        .card-custom {
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
        .titulo h2 { margin: 0; font-size: 1.8rem; }
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

<?php include_once("../navegacion/nav.php"); ?>

<div class="container">
    <div class="card-custom">
        <div class="titulo">
            <h2>Catálogo de Grupos</h2>
        </div>

        <div class="contenido">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="../registro/reg_grupo.php" class="btn btn-sae">
                    <i class="bi bi-people-fill me-1"></i> Registrar nuevo grupo
                </a>
                <a href="../registro/reg_grupo.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Regresar al registro
                </a>
            </div>

            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Clave del Grupo</th>
                        <th scope="col">Nombre del Grupo / Semestre</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($grupo = $resultado->fetch_assoc()): ?>
                            <tr>
                                <th scope="row"><?php echo $grupo['idgrupo']; ?></th>
                                <td><?php echo htmlspecialchars($grupo['clave_grupo']); ?></td>
                                <td><?php echo htmlspecialchars($grupo['nombre_grupo']); ?></td>
                                <td class="text-center">
                                    <a href="../modificacion/modif_grupo.php?idgrupo=<?php echo $grupo['idgrupo']; ?>" class="btn btn-primary btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="../baja/baja_grupo.php?idgrupo=<?php echo $grupo['idgrupo']; ?>" class="btn btn-danger btn-sm" title="Dar de Baja" onclick="return confirm('¿Seguro que deseas dar de baja este grupo?');"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay grupos activos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pie">
            Catálogo de grupos - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

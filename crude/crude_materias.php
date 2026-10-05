<?php
include_once("../conexion.php");

$sql = "SELECT * FROM materias WHERE estatus_mat = 'ALTA'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Materias - SAE</title>
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
            <h2>Catálogo de Materias</h2>
        </div>

        <div class="contenido">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="../registro/reg_materia.php" class="btn btn-sae">
                    <i class="bi bi-journal-plus me-1"></i> Registrar nueva materia
                </a>
                <a href="../registro/reg_materia.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Regresar al registro
                </a>
            </div>

            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Clave</th>
                        <th scope="col">Nombre de la Materia</th>
                        <th scope="col">Créditos</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($materia = $resultado->fetch_assoc()): ?>
                            <tr>
                                <th scope="row"><?php echo $materia['idmat']; ?></th>
                                <td><?php echo htmlspecialchars($materia['clave_mat']); ?></td>
                                <td><?php echo htmlspecialchars($materia['nombre_mat']); ?></td>
                                <td><?php echo htmlspecialchars($materia['creditos']); ?></td>
                                <td class="text-center">
                                    <a href="../modificacion/modif_materia.php?idmat=<?php echo $materia['idmat']; ?>" class="btn btn-primary btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="../baja/baja_materia.php?idmat=<?php echo $materia['idmat']; ?>" class="btn btn-danger btn-sm" title="Dar de Baja" onclick="return confirm('¿Seguro que deseas dar de baja esta materia?');"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay materias activas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pie">
            Catálogo de materias - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

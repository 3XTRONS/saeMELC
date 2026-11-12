<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

if (isset($_GET['baja'])) {
    $id = intval($_GET['baja']);
    $conexion->query("UPDATE usuarios SET estatus_usuario = 'BAJA' WHERE id_usuario = $id");
    header("Location: crude_usuarios.php");
    exit;
}

$sql = "SELECT u.id_usuario, u.nombre_usuario, u.username, u.password, p.nombre_puesto 
        FROM usuarios u
        INNER JOIN puestos p ON u.id_puesto = p.id_puesto
        WHERE u.estatus_usuario = 'ALTA'";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Usuarios</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 950px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 25px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-sae:hover { background-color: #881337; color: white; text-decoration: none; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="formulario">
        <div class="titulo"><h2>Catálogo de Usuarios</h2></div>
        <div class="contenido">
            <div class="mb-4">
                <a href="../registro/reg_usuario.php" class="btn-sae">+ Nuevo Usuario</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle m-0">
                    <thead style="background-color: #212529; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Username</th>
                            <th>Puesto</th>
                            <th>Contraseña (SHA-1)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><b><?php echo $row['id_usuario']; ?></b></td>
                                <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_puesto']); ?></td>
                                <td><code class="text-danger"><?php echo htmlspecialchars($row['password']); ?></code></td>
                                <td>
                                    <a href="crude_usuarios.php?baja=<?php echo $row['id_usuario']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Dar de baja este usuario?');">Baja</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-3 text-muted">No hay usuarios activos.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pie">Catálogo de usuarios - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

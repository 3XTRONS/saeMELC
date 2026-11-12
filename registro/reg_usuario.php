<?php
include_once("../conexion.php");
include_once("../navegacion/nav.php");

$mensaje = "";
$puestos_db = $conexion->query("SELECT id_puesto, nombre_puesto FROM puestos WHERE estatus_puesto = 'ALTA'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $username       = trim($_POST['username'] ?? '');
    $password_raw   = trim($_POST['password'] ?? '');
    $id_puesto      = intval($_POST['id_puesto'] ?? 0);

    if (!empty($nombre_usuario) && !empty($username) && !empty($password_raw) && $id_puesto > 0) {
        $password_sha1 = sha1($password_raw);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, username, password, id_puesto, estatus_usuario) VALUES (?, ?, ?, ?, 'ALTA')");
        $stmt->bind_param("sssi", $nombre_usuario, $username, $password_sha1, $id_puesto);
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success mt-3'>¡Usuario registrado con contraseña encriptada (SHA-1) correctamente!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 850px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .contenido { padding: 30px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="formulario">
        <div class="titulo"><h2>Registro de Usuarios</h2></div>
        <div class="contenido">
            <?php echo $mensaje; ?>
            <form method="POST" action="reg_usuario.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Nombre Completo</label>
                        <input type="text" name="nombre_usuario" class="form-control" placeholder="Ej. Lopez Cornejo Martin Emmanuel" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control" placeholder="Ej. Fazt.admin" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Se guardará encriptada con SHA-1.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Puesto</label>
                        <select name="id_puesto" class="form-control" required>
                            <option value="">Selecciona un puesto...</option>
                            <?php while ($p = $puestos_db->fetch_assoc()): ?>
                                <option value="<?php echo $p['id_puesto']; ?>"><?php echo htmlspecialchars($p['nombre_puesto']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-sae mr-2">Guardar Usuario</button>
                <a href="../crude/crude_usuarios.php" class="btn btn-outline-secondary">Ver Catálogo</a>
            </form>
        </div>
        <div class="pie">Registro de usuarios - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

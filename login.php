<?php
session_start();
include_once("conexion.php");

if (isset($_SESSION['id_usuario'])) {
    header("Location: principal.php");
    exit;
}

$error = "";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $usuario = $_POST['username'];
    $clave = sha1($_POST['password']);

    $sql = "SELECT u.id_usuario, u.nombre_usuario, u.id_puesto, p.nombre_puesto 
            FROM usuarios u 
            INNER JOIN puestos p ON u.id_puesto = p.id_puesto 
            WHERE u.username = '$usuario' AND u.password = '$clave' AND u.estatus_usuario = 'ALTA'";
    
    $res = $conexion->query($sql);

    if ($res && $res->num_rows > 0) {
        $datos = $res->fetch_assoc();
        $_SESSION['id_usuario'] = $datos['id_usuario'];
        $_SESSION['nombre_usuario'] = $datos['nombre_usuario'];
        $_SESSION['id_puesto'] = $datos['id_puesto'];
        $_SESSION['nombre_puesto'] = $datos['nombre_puesto'];

        header("Location: principal.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - SAE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f3f1f4; }
        .caja { background: white; max-width: 400px; margin: 80px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .encabezado { background-color: #172554; color: white; padding: 20px; text-align: center; }
        .cuerpo { padding: 25px; }
        .btn-entrar { background-color: #9f1239; color: white; width: 100%; border: none; padding: 10px; border-radius: 4px; }
        .btn-entrar:hover { background-color: #881337; color: white; }
    </style>
</head>
<body>
<div class="caja">
    <div class="encabezado">
        <h4>Sistema Escolar SAE</h4>
    </div>
    <div class="cuerpo">
        <?php if ($error != "") { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-entrar">Iniciar Sesión</button>
        </form>
    </div>
</div>
</body>
</html>

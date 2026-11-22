<?php
session_start();
include_once("conexion.php");

// Si ya tiene sesión activa, mandarlo directo al sistema
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['id_puesto'] == 4) {
        header("Location: reportes/rep_boleta.php");
    } else {
        header("Location: navegacion/nav.php");
    }
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = sha1(trim($_POST['password'] ?? ''));

    $stmt = $conexion->prepare("
        SELECT u.id_usuario, u.nombre_usuario, u.username, u.id_puesto, u.idalumn, p.nombre_puesto
        FROM usuarios u
        INNER JOIN puestos p ON u.id_puesto = p.id_puesto
        WHERE u.username = ? AND u.password = ? AND u.estatus_usuario = 'ALTA'
        LIMIT 1
    ");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        $_SESSION['id_usuario']     = $user['id_usuario'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['username']       = $user['username'];
        $_SESSION['id_puesto']      = (int)$user['id_puesto'];
        $_SESSION['nombre_puesto']  = $user['nombre_puesto'];
        $_SESSION['idalumn']        = $user['idalumn'];

        if ($_SESSION['id_puesto'] == 4) {
            header("Location: reportes/rep_boleta.php");
        } else {
            header("Location: navegacion/nav.php");
        }
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - SAE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f3f1f4; }
        .login-card { background-color: white; max-width: 420px; margin: 80px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 20px; text-align: center; }
        .contenido { padding: 30px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 10px; border-radius: 4px; width: 100%; font-weight: bold; }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie { text-align: center; padding: 12px; color: #666; font-size: 0.85rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="login-card">
        <div class="titulo">
            <h4 class="m-0">Sistema de Administración Escolar</h4>
            <small>Control de Acceso</small>
        </div>
        <div class="contenido">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label class="font-weight-bold">Usuario</label>
                    <input type="text" name="username" class="form-control" placeholder="Ingresa tu usuario" required autofocus>
                </div>
                <div class="form-group mb-4">
                    <label class="font-weight-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-sae">Entrar</button>
            </form>
        </div>
        <div class="pie">SAE - Lopez Cornejo Martin Emmanuel</div>
    </div>
</div>
</body>
</html>

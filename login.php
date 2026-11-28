<?php
require_once __DIR__ . "/conexion.php";

if (isset($_SESSION["id_user"])) {
    header("Location: " . $base . "/principal.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = trim($_POST["usuario"] ?? "");
    $pass = sha1($_POST["password"] ?? "");

    $stmt = $conexion->prepare(
        "SELECT u.ID_USER, u.NOMBREUSER, u.PUESTO, u.ID_PROFESORES, u.ID_ALUMNOS, p.DESCRIPCION_PUESTO
         FROM usuarios u
         INNER JOIN puesto p ON p.ID_PUESTO = u.PUESTO
         WHERE u.NOMBREUSER = ? AND u.PWUSER = ? AND u.STATUS_USER = 'ALTA'"
    );
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($fila) {
        $_SESSION["id_user"]     = (int)$fila["ID_USER"];
        $_SESSION["nombre_user"] = $fila["NOMBREUSER"];
        $_SESSION["puesto"]      = (int)$fila["PUESTO"];
        $_SESSION["rol_nombre"]  = strtoupper($fila["DESCRIPCION_PUESTO"]);
        $_SESSION["id_profesor"] = $fila["ID_PROFESORES"];
        $_SESSION["id_alumno"]   = $fila["ID_ALUMNOS"];

        header("Location: " . $base . "/principal.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SAE MELC</title>
    <link href="<?= $base ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f1f4; }
        .bg-sae-zafiro { background-color: #172554; color: #fff; }
        .btn-sae { background-color: #9f1239; border-color: #9f1239; color: #fff; }
        .btn-sae:hover { background-color: #881337; border-color: #881337; color: #fff; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-sae-zafiro text-center py-3">
                    <h4 class="mb-0">Sistema Escolar SAE</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error !== "") { ?>
                        <div class="alert alert-danger"><?= h($error) ?></div>
                    <?php } ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-sae w-100">Iniciar Sesión</button>
                    </form>
                </div>
                <div class="card-footer text-center text-muted small">
                    Programación II - Lopez Cornejo Martin Emmanuel
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conexion = new mysqli("localhost", "root", "", "saemelc");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

// Calcula /saeMELC automáticamente desde la raíz
$docRoot = str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]);
$appRoot = str_replace("\\", "/", __DIR__);
$base = str_replace($docRoot, "", $appRoot);

function h($texto): string {
    return htmlspecialchars((string)$texto, ENT_QUOTES, "UTF-8");
}

function esAdmin(): bool {
    return (int)($_SESSION["puesto"] ?? 0) === 1;
}

function esMaestro(): bool {
    return (int)($_SESSION["puesto"] ?? 0) === 2;
}

function esAlumno(): bool {
    return (int)($_SESSION["puesto"] ?? 0) === 3;
}

function requerirLogin(): void {
    global $base;
    if (!isset($_SESSION["id_user"])) {
        header("Location: " . $base . "/login.php");
        exit;
    }
}

function requerirRol(array $rolesPermitidos): void {
    global $base;
    requerirLogin();
    $rolActual = strtoupper($_SESSION["rol_nombre"] ?? "");
    if (!in_array($rolActual, $rolesPermitidos, true)) {
        header("Location: " . $base . "/principal.php");
        exit;
    }
}
?>

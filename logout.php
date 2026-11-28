<?php
require_once __DIR__ . "/conexion.php";
session_unset();
session_destroy();
header("Location: " . $base . "/login.php");
exit;
?>

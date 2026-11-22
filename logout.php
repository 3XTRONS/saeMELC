<?php
session_start();
session_unset();    // Limpia todas las variables de sesión
session_destroy();  // Destruye la sesión en el servidor
header("Location: login.php");
exit;
?>

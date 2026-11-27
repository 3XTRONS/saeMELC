<?php
@session_start();

if (isset($_SESSION['id_usuario'])) {
    header("Location: principal.php");
} else {
    header("Location: login.php");
}
exit;
?>

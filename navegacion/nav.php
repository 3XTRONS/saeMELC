<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    $login_url = file_exists("login.php") ? "login.php" : "../login.php";
    header("Location: " . $login_url);
    exit;
}

$rol = $_SESSION['id_puesto'];
$ruta = file_exists("conexion.php") ? "" : "../";

include_once("header.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="<?php echo $ruta; ?>principal.php">SAE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">

      <?php if ($rol == 1 || $rol == 2) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuRegistros" data-toggle="dropdown">Registros</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_alumno.php">Alumnos</a>
          <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_grupos.php">Grupos</a>
          <?php if ($rol == 1) { ?>
            <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_profesores.php">Profesores</a>
            <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_materias.php">Materias</a>
            <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_unidades.php">Unidades</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_puestos.php">Puestos</a>
            <a class="dropdown-item" href="<?php echo $ruta; ?>crude/crude_usuarios.php">Usuarios</a>
          <?php } ?>
        </div>
      </li>
      <?php } ?>

      <?php if ($rol == 1 || $rol == 2) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuProcesos" data-toggle="dropdown">Procesos</a>
        <div class="dropdown-menu">
          <?php if ($rol == 1) { ?>
            <a class="dropdown-item" href="<?php echo $ruta; ?>procesos/inscripcion_grupo.php">Inscripción a grupos</a>
            <a class="dropdown-item" href="<?php echo $ruta; ?>procesos/asignacion_materia.php">Asignación de materias</a>
          <?php } ?>
          <a class="dropdown-item" href="<?php echo $ruta; ?>procesos/captura_calificaciones.php">Captura de calificaciones</a>
        </div>
      </li>
      <?php } ?>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuReportes" data-toggle="dropdown">Reportes</a>
        <div class="dropdown-menu">
          <?php if ($rol == 1 || $rol == 2) { ?>
            <a class="dropdown-item" href="<?php echo $ruta; ?>reportes/rep_alumnos_grupo.php">Alumnos por grupo</a>
            <a class="dropdown-item" href="<?php echo $ruta; ?>reportes/rep_calificaciones.php">Calificaciones por materia y grupo</a>
          <?php } ?>
          <?php if ($rol == 1) { ?>
            <a class="dropdown-item" href="<?php echo $ruta; ?>reportes/rep_materias_alumno.php">Materias por alumno</a>
          <?php } ?>
          <?php if ($rol == 1 || $rol == 4) { ?>
            <a class="dropdown-item" href="<?php echo $ruta; ?>reportes/rep_boleta.php">Boleta de calificaciones</a>
          <?php } ?>
        </div>
      </li>

    </ul>

    <span class="navbar-text text-white mr-3">
      <?php echo $_SESSION['nombre_usuario']; ?> (<?php echo $_SESSION['nombre_puesto']; ?>)
    </span>
    <a href="<?php echo $ruta; ?>logout.php" class="btn btn-danger btn-sm">Salir</a>
  </div>
</nav>

<?php include_once("footer.php"); ?>

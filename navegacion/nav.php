<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no ha iniciado sesión, lo regresa al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$rol = (int)$_SESSION['id_puesto']; // 1=Admin, 2=Docentes, 3=Coordinador, 4=Alumno

include_once("header.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="../navegacion/nav.php">SAE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">

      <!-- REGISTROS: Admin (1) ve todo | Docentes (2) solo Alumnos y Grupos -->
      <?php if ($rol === 1 || $rol === 2): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuRegistros" role="button" data-toggle="dropdown">
          Registros
        </a>
        <div class="dropdown-menu" aria-labelledby="menuRegistros">
          <a class="dropdown-item" href="../crude/crude_alumno.php">Alumnos</a>
          <a class="dropdown-item" href="../crude/crude_grupos.php">Grupos</a>
          <?php if ($rol === 1): ?>
            <a class="dropdown-item" href="../crude/crude_profesores.php">Profesores</a>
            <a class="dropdown-item" href="../crude/crude_materias.php">Materias</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../crude/crude_puestos.php">Puestos</a>
            <a class="dropdown-item" href="../crude/crude_usuarios.php">Usuarios</a>
          <?php endif; ?>
        </div>
      </li>
      <?php endif; ?>

      <!-- PROCESOS: Admin (1) ve todo | Docentes (2) solo Captura de calificaciones -->
      <?php if ($rol === 1 || $rol === 2): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuProcesos" role="button" data-toggle="dropdown">
          Procesos
        </a>
        <div class="dropdown-menu" aria-labelledby="menuProcesos">
          <?php if ($rol === 1): ?>
            <a class="dropdown-item" href="../procesos/inscripcion_grupo.php">Inscripción a grupos</a>
            <a class="dropdown-item" href="../procesos/asignacion_materia.php">Asignación de materias</a>
          <?php endif; ?>
          <a class="dropdown-item" href="../procesos/captura_calificaciones.php">Captura de calificaciones</a>
        </div>
      </li>
      <?php endif; ?>

      <!-- REPORTES: Admin (1) ve los 4 | Docentes (2) ve Grupo y Calificaciones | Alumno (4) solo su Boleta -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menuReportes" role="button" data-toggle="dropdown">
          Reportes
        </a>
        <div class="dropdown-menu" aria-labelledby="menuReportes">
          <?php if ($rol === 1 || $rol === 2): ?>
            <a class="dropdown-item" href="../reportes/rep_alumnos_grupo.php">Alumnos por grupo</a>
            <a class="dropdown-item" href="../reportes/rep_calificaciones.php">Calificaciones por materia y grupo</a>
          <?php endif; ?>
          <?php if ($rol === 1): ?>
            <a class="dropdown-item" href="../reportes/rep_materias_alumno.php">Materias por alumno</a>
          <?php endif; ?>
          <?php if ($rol === 1 || $rol === 4): ?>
            <a class="dropdown-item" href="../reportes/rep_boleta.php">
              <?php echo ($rol === 4) ? 'Mis Calificaciones (Boleta)' : 'Boleta de calificaciones'; ?>
            </a>
          <?php endif; ?>
        </div>
      </li>

    </ul>

    <!-- Usuario activo y botón Salir -->
    <span class="navbar-text text-white mr-3">
      <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?> 
      <span class="badge badge-light text-dark ml-1"><?php echo htmlspecialchars($_SESSION['nombre_puesto']); ?></span>
    </span>
    <a href="../logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
  </div>
</nav>

<?php include_once("footer.php"); ?>

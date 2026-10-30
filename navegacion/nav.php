<?php include_once("header.php"); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">SAE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      
      <!-- REGISTROS / CATÁLOGOS -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Registros
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="../crude/crude_alumno.php">Alumnos</a>
          <a class="dropdown-item" href="../crude/crude_profesores.php">Profesores</a>
          <a class="dropdown-item" href="../crude/crude_materias.php">Materias</a>
          <a class="dropdown-item" href="../crude/crude_grupos.php">Grupos</a>
          <a class="dropdown-item" href="../crude/crude_unidades.php">Unidades</a>
        </div>
      </li>

      <!-- PROCESOS (SOLO LAS 3 OPCIONES PEDIDAS POR EL PROFE) -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Procesos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
          <a class="dropdown-item" href="../procesos/inscripcion_grupo.php">Inscripción a grupos</a>
          <a class="dropdown-item" href="../procesos/asignacion_materia.php">Asignación de materias</a>
          <a class="dropdown-item" href="../procesos/captura_calificaciones.php">Captura de calificaciones</a>
        </div>
      </li>

      <!-- REPORTES -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Reportes
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
          <a class="dropdown-item" href="../reportes/rep_general.php">Reporte General</a>
        </div>
      </li>

    </ul>
  </div>
</nav>

<?php include_once("footer.php"); ?>

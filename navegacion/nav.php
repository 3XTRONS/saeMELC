<?php include_once("header.php"); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">SAE</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      
      <!-- CATÁLOGOS -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown">
          Catálogos
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="../crude/crude_alumno.php">Alumnos</a>
          <a class="dropdown-item" href="../crude/crude_profesores.php">Profesores</a>
          <a class="dropdown-item" href="../crude/crude_materias.php">Materias</a>
          <a class="dropdown-item" href="../crude/crude_grupos.php">Grupos</a>
        </div>
      </li>

      <!-- PROCESOS -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown">
          Registros
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="../registro/reg_alumno.php">Registro Alumno</a>
          <a class="dropdown-item" href="../registro/reg_profesor.php">Registro Profesor</a>
          <a class="dropdown-item" href="../registro/reg_materia.php">Registro Materia</a>
          <a class="dropdown-item" href="../registro/reg_grupo.php">Registro Grupo</a>
        </div>
      </li>

      <!-- REPORTES -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown">
          Reportes
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="../reportes/rep_general.php">Reporte General</a>
        </div>
      </li>

    </ul>
  </div>
</nav>

<?php include_once("footer.php"); ?>

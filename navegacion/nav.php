<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sae</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <!-- Menú Catálogos (CRUD) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCatalogos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Catalogos
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownCatalogos">
            <li><a class="dropdown-item" href="../crude/crude_alumno.php">Alumnos</a></li>
            <li><a class="dropdown-item" href="../crude/crude_profesores.php">Profesores</a></li>
            <li><a class="dropdown-item" href="../crude/crude_materias.php">Materias</a></li>
            <li><a class="dropdown-item" href="../crude/crude_grupos.php">Grupos</a></li>
          </ul>
        </li>

        <!-- Menú Procesos (Registros) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProcesos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Procesos
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownProcesos">
            <li><a class="dropdown-item" href="../registro/reg_alumno.php">Registro Alumno</a></li>
            <li><a class="dropdown-item" href="../registro/reg_profesor.php">Registro Profesor</a></li>
            <li><a class="dropdown-item" href="../registro/reg_materia.php">Registro Materia</a></li>
            <li><a class="dropdown-item" href="../registro/reg_grupo.php">Registro Grupo</a></li>
          </ul>
        </li>

        <!-- Menú Reportes -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReportes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Reportes
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownReportes">
            <li><a class="dropdown-item" href="#">Reporte General</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

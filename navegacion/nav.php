<?php
requerirLogin();
$rolTexto = $_SESSION["rol_nombre"] ?? "";
$usuarioTexto = $_SESSION["nombre_user"] ?? "";
?>
<style>
    body { background-color: #f3f1f4 !important; }
    .bg-sae-zafiro { background-color: #172554 !important; color: #ffffff !important; }
    .btn-sae { background-color: #9f1239 !important; border-color: #9f1239 !important; color: #ffffff !important; }
    .btn-sae:hover { background-color: #881337 !important; border-color: #881337 !important; color: #ffffff !important; }
    .badge-sae { background-color: #9f1239 !important; color: #ffffff !important; }
    @media print {
        nav, .navbar, .no-imprimir { display: none !important; }
        body { background: #ffffff !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-sae-zafiro shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= $base ?>/principal.php">SAE MELC</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navPrincipal">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <?php if (esAdmin()) { ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Registros</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_alumno.php">Alumnos</a></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_profesores.php">Profesores</a></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_grupos.php">Grupos</a></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_materias.php">Materias</a></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_unidades.php">Unidades</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_puestos.php">Puestos</a></li>
            <li><a class="dropdown-item" href="<?= $base ?>/crude/crude_usuarios.php">Usuarios</a></li>
          </ul>
        </li>
        <?php } ?>

        <?php if (esAdmin() || esMaestro()) { ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Procesos</a>
          <ul class="dropdown-menu">
            <?php if (esAdmin()) { ?>
              <li><a class="dropdown-item" href="<?= $base ?>/procesos/inscripcion_grupo.php">Inscripción a grupos</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>/procesos/asignacion_materia.php">Asignación de materias</a></li>
            <?php } ?>
            <li><a class="dropdown-item" href="<?= $base ?>/procesos/captura_calificaciones.php">Captura de calificaciones</a></li>
          </ul>
        </li>
        <?php } ?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Reportes</a>
          <ul class="dropdown-menu">
            <?php if (esAdmin() || esMaestro()) { ?>
              <li><a class="dropdown-item" href="<?= $base ?>/reportes/rep_alumnos_grupo.php">Alumnos por grupo</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>/reportes/rep_calificaciones.php">Calificaciones por materia y grupo</a></li>
            <?php } ?>
            <?php if (esAdmin()) { ?>
              <li><a class="dropdown-item" href="<?= $base ?>/reportes/rep_materias_alumno.php">Materias por alumno</a></li>
            <?php } ?>
            <li><a class="dropdown-item" href="<?= $base ?>/reportes/rep_boleta.php">Boleta de calificaciones</a></li>
          </ul>
        </li>

      </ul>

      <div class="d-flex align-items-center gap-2">
        <span class="text-white small"><?= h($usuarioTexto) ?></span>
        <span class="badge badge-sae"><?= h($rolTexto) ?></span>
        <a href="<?= $base ?>/logout.php" class="btn btn-outline-light btn-sm ms-2">Salir</a>
      </div>
    </div>
  </div>
</nav>

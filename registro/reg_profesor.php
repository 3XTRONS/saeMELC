<?php
$mensaje = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $mensaje = '<div class="alert alert-success mt-3">¡Profesor registrado correctamente!</div>';
    } elseif ($_GET['status'] == 'error') {
        $msgError = $_GET['msg'] ?? 'Ocurrió un error inesperado.';
        $mensaje = '<div class="alert alert-danger mt-3">Error al registrar: ' . htmlspecialchars($msgError) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Profesores - SAE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f3f1f4; }
        .formulario { background-color: white; max-width: 1000px; margin: 30px auto; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); overflow: hidden; }
        .titulo { background-color: #172554; color: white; padding: 15px; text-align: center; }
        .titulo h2 { margin: 0; font-size: 1.8rem; }
        .contenido { padding: 25px; }
        .btn-sae { background-color: #9f1239; color: white; border: none; padding: 8px 16px; border-radius: 4px; }
        .btn-sae:hover { background-color: #881337; color: white; }
        .pie { text-align: center; padding: 15px; color: #666; font-size: 0.9rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>

<?php include_once("../navegacion/nav.php"); ?>

<div class="container">
    <div class="formulario">
        <div class="titulo">
            <h2>Registro de Profesores</h2>
        </div>

        <div class="contenido">
            <?php echo $mensaje; ?>

            <form method="post" action="../procesos/insert_profesor.php">
                <div class="mb-3">
                    <label class="form-label">ID Profesor</label>
                    <input type="number" name="idprof" class="form-control" required placeholder="Ej. 101">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej. Eva Sara">
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" required placeholder="Ej. García">
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido materno</label>
                    <input type="text" name="apellido_materno" class="form-control" required placeholder="Ej. Hernandez">
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" required placeholder="algunprofe@gmail.com">
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-sae">Registrar profesor</button>
                    <a href="../crude/crude_profesores.php" class="btn btn-outline-secondary">Regresar al catálogo</a>
                </div>
            </form>
        </div>

        <div class="pie">
            Registro de profesores - Programación II Emmanuel Lopez Cornejo
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once 'conexion.php';

// =====================
// PROCESAR FORMULARIO
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibir datos y limpiar
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $duracion    = trim($_POST['duracion'] ?? '');
    $precio      = trim($_POST['precio'] ?? '');
    $hora        = trim($_POST['hora'] ?? '');

    // Validación mínima: sólo tratar como vacío las cadenas vacías
    if ($nombre === '' || $descripcion === '' || $duracion === '' || $precio === '' || $hora === '') {
        $_SESSION['mensaje_error'] = "Debes completar todos los campos obligatorios.";
        header("Location: servicio.php");
        exit;
    }

    try {
        // INSERT de actividad
        $sql = "INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
                VALUES (:nombre, :descripcion, :duracion, :precio, :hora)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nombre'      => $nombre,
            ':descripcion' => $descripcion,
            ':duracion'    => $duracion,
            ':precio'      => $precio,
            ':hora'        => $hora
        ]);

        $_SESSION['mensaje_exito'] = "✅ Actividad agregada correctamente.";
        header("Location: servicio.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "❌ Error al insertar la actividad: " . $e->getMessage();
        header("Location: servicio.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="favicon/favicon.ico">
    <title>Nueva Actividad</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Nueva Actividad</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<div class="container">

<!-- ======================= -->
<!--   MENSAJES DE SESIÓN    -->
<!-- ======================= -->
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION['mensaje_exito']; ?>
    </div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="mensaje-error">
        <?= $_SESSION['mensaje_error']; ?>
    </div>
    <?php unset($_SESSION['mensaje_error']); ?>
<?php endif; ?>

<h1 class="titulo-club">Agregar nueva actividad</h1>

<main>
<form action="" method="post" enctype="multipart/form-data" id="formulario-servicio">

    <div class="bloque-form">
        <label for="nombre">Nombre de la actividad</label>
        <input type="text" id="nombre" name="nombre" placeholder="Club de lectura, reunión temática…">
        <span id="nombreError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="descripcion">Descripción</label>
        <textarea maxlength="300" id="descripcion" name="descripcion"></textarea>
        <p><span id="contador">0</span>/300 caracteres</p>
        <span id="descripcionError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="duracion">Duración (minutos)</label>
        <input type="text" id="duracion" name="duracion">
        <span id="duracionError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="precio">Precio</label>
        <input type="number" id="precio" name="precio">
        <span id="precioError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="hora">Hora</label>
        <input type="time" id="hora" name="hora" placeholder="HH:MM">
        <span id="horaError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Crear Actividad</span></button>
        <a href="servicios.php" class="btn-atras"><span>Volver</span></a>
    </div>

</form>
</main>

<div id="footer"></div>

<script src="js/funcionesServicio.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</body>
</html>

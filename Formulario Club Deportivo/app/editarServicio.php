<?php
require_once 'conexion.php';

// ========================
// 1. Comprobar si llega un ID
// ========================
if (!isset($_GET['id'])) {
    header("Location: servicios.php");
    exit;
}

$id = $_GET['id'];

// ========================
// 2. Cargar los datos del servicio
// ========================
$sql = "SELECT * FROM servicio WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    header("Location: servicios.php");
    exit;
}

// ========================
// 3. Procesar formulario POST
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];
    $hora = $_POST['hora'];  // <--- NUEVO

    $sqlUpdate = "UPDATE servicio SET 
                    nombre = :nombre,
                    descripcion = :descripcion,
                    duracion = :duracion,
                    precio = :precio,
                    hora = :hora     -- <--- NUEVO
                  WHERE id = :id";

    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'duracion' => $duracion,
        'precio' => $precio,
        'hora' => $hora,      // <--- NUEVO
        'id' => $id
    ]);

    header("Location: servicios.php?edit_ok=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="favicon/favicon.ico">
    <title>Editar servicio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="servicios-body">

<h1 class="titulo-club">Editar servicio</h1>

<div id="nav"></div>

<div class="container">

<main>

<form method="post" enctype="multipart/form-data" id="formulario-servicio">

    <div class="bloque-form">
        <label for="nombre">Nombre de la actividad</label>
        <input type="text" id="nombre" name="nombre"
               value="<?= htmlspecialchars($servicio['nombre']) ?>">
        <span id="nombreError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" maxlength="300"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>
        <p><span id="contador"><?= strlen($servicio['descripcion']) ?></span>/300 caracteres</p>
        <span id="descripcionError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="duracion">Duración (minutos)</label>
        <input type="number" id="duracion" name="duracion"
               value="<?= htmlspecialchars($servicio['duracion']) ?>">
        <span id="duracionError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="precio">Costo (€)</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01"
               value="<?= htmlspecialchars($servicio['precio']) ?>">
        <?php if (floatval($servicio['precio']) == 0): ?>
            <p class="nota">Actual: Gratuito</p>
        <?php endif; ?>
        <span id="precioError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="hora">Hora</label>
        <input type="time" id="hora" name="hora"
               value="<?= htmlspecialchars($servicio['hora']) ?>">
        <span id="horaError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Guardar cambios</span></button>
        <a href="servicios.php" class="btn-atras"><span>Cancelar</span></a>
    </div>

</form>



</main>
</div>

<div id="footer"></div>

<script src="js/nav.js"></script>
<script src="js/footer.js"></script>
<script src="js/funcionesEditarServicio.js"></script>
<script src="js/transiciones.js"></script>
</body>
</html>

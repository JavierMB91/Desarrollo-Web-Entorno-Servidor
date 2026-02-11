<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    // Validación básica mínima (el JS ya valida todo)
    if (!$nombre || !$edad || !$password || !$telefono) {
        $_SESSION['mensaje_error'] = "Debes completar todos los campos obligatorios.";
        header("Location: socio.php");
        exit;
    }

    // Hashear contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $rol = 'socio';

    // SUBIDA DE FOTO
    $fotoNombre = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg'];

        if (!in_array($ext, $extPermitidas)) {
            $_SESSION['mensaje_error'] = "Formato de imagen no permitido (solo JPG/JPEG).";
            header("Location: socio.php");
            exit;
        }

        // Limpia el teléfono para usarlo como nombre de archivo
        $telefonoLimpio = preg_replace('/\D/', '', $telefono);

        $fotoNombre = $telefonoLimpio . '.' . $ext;

        $rutaDestino = __DIR__ . '/uploads/usuarios/' . $fotoNombre;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $_SESSION['mensaje_error'] = "Error al subir la imagen.";
            header("Location: socio.php");
            exit;
        }
    }

    // Insertar en BD
    try {
        $stmt = $pdo->prepare("INSERT INTO usuario (nombre, edad, password, rol, telefono, foto, fecha_registro)
                       VALUES (:nombre, :edad, :password, :rol, :telefono, :foto, NOW())");

        $stmt->execute([
            ':nombre'   => $nombre,
            ':edad'     => $edad,
            ':password' => $passwordHash,
            ':rol'      => $rol,
            ':telefono' => $telefono,
            ':foto'     => $fotoNombre
        ]);

        $_SESSION['mensaje_exito'] = "Usuario registrado correctamente.";
        header("Location: socio.php");
        exit;

    } catch (PDOException $e) {
    $_SESSION['mensaje_error'] = "Error al registrar el usuario: " . $e->getMessage();
    header("Location: socio.php");
    exit;
}

}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Registro de Miembro"; ?>
    <?php include 'head.php'; ?>
</head>

<body class="socio-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-principal">Registro de Miembro</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<main>

<!-- =======================
     MENSAJES DE SESIÓN
======================= -->
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



<form action="" method="post" enctype="multipart/form-data" id="formularioSocio">

    <div class="bloque-form">
        <label for="nombre">Nombre completo</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
        <span id="nombreError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="edad">Edad</label>
        <input type="number" name="edad" id="edad" placeholder="Edad">
        <span id="edadError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Contraseña">
        <span id="passwordError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" maxlength="12" placeholder="653 48 78 96">
        <span id="telefonoError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="foto">Foto del miembro</label>
        <input type="file" name="foto" id="foto">
        <span id="fotoError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Registrarse</span></button>
        <a href="index.php" class="btn-atras"><span>Volver</span></a>
    </div>

</form>

</main>

<div id="footer"></div>

<script src="js/funcionesSocio.js?v=<?= filemtime('js/funcionesSocio.js') ?>"></script>
<script src="js/footer.js?v=<?= filemtime('js/footer.js') ?>"></script>
<script src="js/transiciones.js?v=<?= filemtime('js/transiciones.js') ?>"></script>

</div>
</body>
</html>

<?php
session_start();
require_once 'conexion.php';

// =====================
// PROCESAR FORMULARIO
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = $_POST['nombre'] ?? '';
    $edad     = $_POST['edad'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $password = $_POST['password'] ?? '';

    $edad = ($edad === '') ? null : $edad;

    // ==========================
    // Hashear clave
    // ==========================
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // ==========================
    // Manejo de imagen
    // ==========================
    $fotoNombre = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $fotoTmp = $_FILES['foto']['tmp_name'];

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $_SESSION['mensaje_error'] = "❌ Formato de imagen no permitido (solo JPG, PNG, WEBP)";
            header("Location: nuevoSocio.php");
            exit;
        }

        $telefonoLimpio = preg_replace('/\D/', '', $telefono);
        $fotoNombre = $telefonoLimpio . "." . $ext;

        $rutaDestino = __DIR__ . "/uploads/usuarios/" . $fotoNombre;

        if (!move_uploaded_file($fotoTmp, $rutaDestino)) {
            $_SESSION['mensaje_error'] = "❌ Error al subir la imagen";
            header("Location: nuevoSocio.php");
            exit;
        }
    }

    // ==========================
    // INSERTAR EN BD
    // ==========================
    try {
        $sql = "INSERT INTO usuario (nombre, edad, telefono, foto, password)
            VALUES (:nombre, :edad, :telefono, :foto, :password)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'   => $nombre,
            ':edad'     => $edad,
            ':telefono' => $telefono,
            ':foto'     => $fotoNombre,
            ':password' => $passwordHash
        ]);

        $_SESSION['mensaje_exito'] = "✅ Socio agregado correctamente";
        header("Location: nuevoSocio.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "❌ Error al insertar: " . $e->getMessage();
        header("Location: nuevoSocio.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Agregar Nuevo Socio"; ?>
    <?php include 'head.php'; ?>
</head>

<body>

<!-- =======================
     MENSAJES DE SESIÓN
======================== -->
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


<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Nuevo Socio</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<div class="container">

<h1 class="titulo-club">Agregar nuevo socio</h1>

<main>
<form action="" method="post" enctype="multipart/form-data" id="formularioNuevoSocio">

<div class="bloque-form">
    <label for="nombre">Nombre Completo</label>
    <input id="nombre" type="text" name="nombre" placeholder="Nombre Completo">
    <span id="errorNombre" class="error"></span>
</div>

<div class="bloque-form">
    <label for="edad">Edad</label>
    <input id="edad" type="number" name="edad" placeholder="Edad" min="1">
    <span id="errorEdad" class="error"></span>
</div>

<div class="bloque-form">
    <label for="telefono">Teléfono</label>
    <input id="telefono" type="tel" name="telefono" placeholder="653 48 78 96">
    <span id="errorTelefono" class="error"></span>
</div>

<div class="bloque-form">
    <label for="password">Clave</label>
    <input id="password" type="password" name="password" placeholder="Clave">
    <span id="passwordError" class="error"></span>
</div>

<div class="bloque-form">
    <label for="foto">Foto</label>
    <input id="foto" type="file" name="foto" accept="image/jpeg, image/png, image/webp">
    <span id="errorFoto" class="error"></span>
</div>

<div class="contenedor-botones">
    <button type="submit"><span>Agregar socio</span></button>
    <a href="socios.php" class="btn-atras"><span>Volver</span></a>
</div>

</form>
</main>

<div id="footer"></div>

</div>

<script src="js/funcionesAnadirSocio.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</body>
</html>
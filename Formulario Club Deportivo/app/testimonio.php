<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado y es Administrador o Socio
if (!isset($_SESSION['id']) || !in_array($_SESSION['rol'] ?? '', ['administrador', 'socio'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Usar siempre el ID del usuario logueado
    $autor_id = $_SESSION['id'];
    $contenido = trim($_POST['contenido'] ?? '');

    if (!$autor_id || !$contenido) {
        $_SESSION['mensaje_error'] = "Debes completar todos los campos.";
        header("Location: testimonio.php");
        exit;
    }

    try {
        // Insertar comentario con fecha actual
        $sql = "INSERT INTO testimonio (autor_id, contenido, fecha)
            VALUES (:autor_id, :contenido, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'autor_id' => $autor_id,
            'contenido' => $contenido,
        ]);

        // Mensaje de éxito
        $_SESSION['mensaje_exito'] = "✅ Comentario agregado correctamente.";

        // Redirigir a comentario.php para mostrar todos los comentarios
        header("Location: comentario.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "❌ Error al registrar el comentario.";
        header("Location: testimonio.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Nuevo Comentario"; ?>
    <?php include 'head.php'; ?>
</head>

<body class="testimonio-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Nuevo Comentario</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<main>

<!-- =======================
     MENSAJES DE SESIÓN
======================= -->
<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="mensaje-error">
        <?= $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
    </div>
<?php endif; ?>

<form action="" method="post" id="formularioTestimonio">

    <div class="bloque-form">
        <label for="autor_id">Autor del comentario</label>
        <input type="text" id="autor_id" value="<?= htmlspecialchars($_SESSION['nombre']) ?>" disabled>
        <!-- El ID se toma de la sesión en el POST -->
        <span id="autorError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="contenido">Comentario</label>
        <textarea maxlength="100" id="contenido" name="contenido"></textarea>
        <p><span id="contador">0</span>/100 caracteres</p>
        <span id="testimonioError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Enviar</span></button>
        <a href="comentario.php" class="btn-atras"><span>Volver</span></a>
    </div>

</form>

</main>

<div id="footer"></div>

<script src="js/funcionesTestimonio.js?v=<?= filemtime('js/funcionesTestimonio.js') ?>"></script>
<script src="js/footer.js?v=<?= filemtime('js/footer.js') ?>"></script>
<script src="js/transiciones.js?v=<?= filemtime('js/transiciones.js') ?>"></script>

</div>
</body>
</html>

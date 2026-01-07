<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $autor_id = $_POST['autor_id'] ?? '';
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
// Obtener todos los usuarios para el select
$stmt = $pdo->query("SELECT id, nombre FROM usuario ORDER BY nombre");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/estilos.css">
    <title>Nuevo Comentario</title>
</head>

<body class="testimonio-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Nuevo Comentario</h1>
    </div>
    <div id="nav"></div>
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
        <select name="autor_id" id="autor_id">
            <?php foreach($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
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

<script src="js/funcionesTestimonio.js"></script>
<script src="js/nav.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</div>
</body>
</html>

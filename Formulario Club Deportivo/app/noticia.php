<?php
session_start();
require_once 'conexion.php';

// Verificar si es administrador para poder crear noticias
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: noticias.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = $_POST["titulo"] ?? '';
    $contenido = $_POST["contenido"] ?? '';
    $fecha = $_POST["fecha"] ?? date('Y-m-d');

    if (!$titulo || !$contenido) {
        $_SESSION['mensaje_error'] = "❌ Título y contenido son obligatorios.";
        header("Location: noticias.php");
        exit;
    }

    try {
        // 1️⃣ Insertar noticia sin imagen
        $sql = "INSERT INTO noticia (titulo, contenido, fecha_publicacion)
            VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $contenido, $fecha]);

        // 2️⃣ Obtener ID generado
        $idNoticia = $pdo->lastInsertId();
        $nombreImagen = null;

        // 3️⃣ Procesar imagen si existe
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
            $extPermitidas = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $extPermitidas)) {
                $_SESSION['mensaje_error'] = "❌ Formato de imagen no permitido.";
                header("Location: noticias.php");
                exit;
            }

            $nombreImagen = $idNoticia . '.' . $ext;
            $rutaDestino = __DIR__ . '/uploads/noticias/' . $nombreImagen;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $_SESSION['mensaje_error'] = "❌ Error al subir la imagen.";
                header("Location: noticias.php");
                exit;
            }

            // 4️⃣ Actualizar noticia con la imagen
            $sqlUpdate = "UPDATE noticia SET imagen = ? WHERE id = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$nombreImagen, $idNoticia]);
        }

        $_SESSION['mensaje_exito'] = "✅ Noticia publicada correctamente.";
        header("Location: noticias.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "❌ Error al registrar la noticia.";
        header("Location: noticias.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <?php $tituloPagina = "Nueva Noticia"; ?>
    <?php include 'head.php'; ?>
</head>
<body class="noticia-body">
    <div class="container">
<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Nueva Noticia</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>
<main>
<form action="" method="post" enctype="multipart/form-data" id="formularioNoticia">
    <div class="bloque-form">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo">
        <span id="tituloError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="contenido">Contenido</label>
        <textarea maxlength="300" id="contenido" name="contenido"></textarea>
        <p><span id="contador">0</span>/300 caracteres</p>
        <span id="noticiaError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="fecha">Fecha de publicación</label>
        <input type="date" id="fecha" name="fecha">
        <span id="fechaError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="imagen">Imagen asociada</label>
        <input type="file" id="imagen" name="imagen">
        <span id="imagenError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Publicar</span></button>
        <a href="noticias.php" class="btn-atras"><span>Volver</span></a>
    </div>
</form>
</main>

<div id="footer"></div>
<script src="js/funcionesNoticia.js"></script>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>
</div>
</body>
</html>

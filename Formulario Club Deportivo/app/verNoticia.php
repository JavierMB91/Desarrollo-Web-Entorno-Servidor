<?php
require_once 'conexion.php';

$id = $_GET["id"];

$sql = "SELECT * FROM noticia WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$noticia = $stmt->fetch(PDO::FETCH_ASSOC);
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
<title>Noticias</title>
</head>
<body class="noticia-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Noticias</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<main class="ver-noticia">
    <h2 class="titulo-noticia"><?= htmlspecialchars($noticia["titulo"]) ?></h2>
    <article class="noticia-detalle">
        <!-- La imagen ahora es clicable -->
        <img id="imagenNoticia" src="uploads/noticias/<?= htmlspecialchars($noticia['imagen']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" class="imagen-noticia clickable">

        <div class="contenido-noticia">
            <!-- Convertimos saltos de línea en <br> pero el contenedor aplica formato legible -->
            <p class="texto-noticia"><?= nl2br(htmlspecialchars($noticia["contenido"])) ?></p>
        </div>

        <div class="contenedor-botones">
            <a href="noticias.php" class="btn-atras"><span>Volver</span></a>
        </div>
    </article>
</main>

<!-- =======================
     VENTANA MODAL
======================== -->
<div id="miModal" class="modal-imagen">
  <span class="cerrar-modal">&times;</span>
  <img class="modal-contenido" id="imagenGrande">
</div>

<div id="footer"></div>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>
<script src="js/modalVerNoticia.js"></script>
</div>
</body>
</html>

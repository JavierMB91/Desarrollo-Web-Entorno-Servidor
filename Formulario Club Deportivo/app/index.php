<?php
require_once 'conexion.php';
session_start();
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
    <title>Inicio</title>
</head>

<body class="index-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-principal">Libreria</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>


<main>
    <div class="principal">

    <!-- ============================ -->
    <!--        BIENVENIDA            -->
    <!-- ============================ -->
    <section class="bienvenida">
        <img src="uploads/img/fondo_principal.jpg" alt="Fondo principal" class="imagen-bienvenida">
        <h2 class="texto-bienvenida">Bienvenido a la Libreria</h2>
    </section>

    <h2 class="titulo-seccion">Conoce nuestra Librería</h2>
    <section>
    <div class="contenedor-video">
        <video controls width="100%">
            <!-- Reemplaza 'video.mp4' con el nombre real de tu archivo -->
            <source src="uploads/video/Libreria__Creando_comunidad.mp4" type="video/mp4">
            Tu navegador no soporta la reproducción de video.
        </video>
    </div>
</section>


    <!-- ============================ -->
    <!--          NOTICIAS            -->
    <!-- ============================ -->
    <h2 class="titulo-seccion">Últimas noticias</h2>
    <section class="lista-noticias">

    <?php
        // Solo noticias cuya fecha ya pasó y solo 3
        $sqlNoticias = "
            SELECT id, titulo, contenido, imagen, fecha_publicacion
            FROM noticia
            WHERE fecha_publicacion <= NOW()
            ORDER BY fecha_publicacion DESC
            LIMIT 3
        ";

        $stmt = $pdo->query($sqlNoticias);

        if ($stmt->rowCount() > 0) {
            echo '<div class="noticias-grid-3">';
            while ($n = $stmt->fetch(PDO::FETCH_ASSOC)) {

                echo '<article class="noticia-card">';
                
                echo '<img src="uploads/noticias/' . htmlspecialchars($n["imagen"]) . '" class="imagen-noticia" alt="' . htmlspecialchars($n["titulo"]) . '" loading="lazy" decoding="async" width="400" height="200">';

                
                echo '<h3>' . htmlspecialchars($n["titulo"]) . '</h3>';

                // 👉 Mostrar fecha formateada
                echo '<p class="fecha-noticia">' . date("d/m/Y", strtotime($n["fecha_publicacion"])) . '</p>';

            
                
                echo '<a class="btn" href="verNoticia.php?id=' . $n["id"] . '">Leer más</a>';
                echo '</article>';
            }
            echo '</div>';
        } else {
            echo "<p>No hay noticias disponibles aún.</p>";
        }
    ?>
</section>



    <!-- ============================ -->
    <!--        TESTIMONIO            -->
    <!-- ============================ -->
    <h2 class="titulo-seccion">Testimonio destacado</h2>
    <section class="testimonio">

    <?php
        // Traer 1 testimonio aleatorio con el nombre del autor (uso de alias correcto)
        $sqlTest = "
            SELECT t.contenido, u.nombre
            FROM testimonio t
            INNER JOIN usuario u ON t.autor_id = u.id
            ORDER BY RAND()
            LIMIT 1
        ";

        $stmtTest = $pdo->query($sqlTest);

        if ($stmtTest && $stmtTest->rowCount() > 0) {
            $t = $stmtTest->fetch(PDO::FETCH_ASSOC);

            echo '<div class="testimonio-box">';
            echo '<p class="texto-testimonio">"' . htmlspecialchars($t['contenido']) . '"</p>';
            echo '<p class="autor-testimonio">- ' . htmlspecialchars($t['nombre']) . '</p>';
            echo '</div>';
        } else {
            echo "<p>No hay testimonios todavía.</p>";
        }
    ?>
</section>



    <!-- ============================ -->
    <!--        ZONA CONTACTO         -->
    <!-- ============================ -->
    <section class="contacto">
        <p>Si desas contactar con nosotros, usa el siguiente formulario:</p>
        <a href="contacto.php" class="btn-atras">Contacto</a>
    </section>
</div>
</main>

<div id="footer"></div>

<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>
<script src="js/asistente.js" defer></script>

</div>
</body>
</html>

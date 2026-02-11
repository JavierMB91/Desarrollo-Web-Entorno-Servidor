<?php
require_once 'conexion.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Inicio"; ?>
    <?php include 'head.php'; ?>
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
        <img src="uploads/img/fondo_principal.jpg" alt="Interior de una librería clásica con estanterías de madera llenas de libros." class="imagen-bienvenida">
        <h2 class="texto-bienvenida">Bienvenido a la Libreria</h2>
    </section>

    <h2 class="titulo-seccion">Conoce nuestra Librería</h2>
    <section>
    <div class="contenedor-video">
        <iframe src="https://www.youtube.com/embed/tMTz4zamdYE" title="Video Librería" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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
                
                echo '<img src="uploads/noticias/' . htmlspecialchars($n["imagen"]) . '" class="imagen-noticia" alt="Imagen para la noticia: ' . htmlspecialchars($n["titulo"]) . '" loading="lazy" decoding="async" width="400" height="200">';

                
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
    <!--    INSTALAR APLICACIÓN (PWA)   -->
    <!-- ============================ -->
    <section id="seccion-instalar" class="instalacion-app" style="display: none;">
        <h2 class="titulo-seccion">Lleva la Librería Contigo</h2>
        <p>
            Instala nuestra aplicación en tu dispositivo para un acceso más rápido y una mejor experiencia.
        </p>
        <div class="contenedor-botones">
            <button id="btn-instalar" class="boton-principal">Instalar Aplicación</button>
        </div>
        <small class="instrucciones-ios">En iPhone/iPad: pulsa el botón 'Compartir' de Safari y luego 'Agregar a pantalla de inicio'.</small>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. REGISTRAR EL SERVICE WORKER (¡Esto faltaba!)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js') // Ruta relativa a index.php (ahora en la raíz)
                .then(registration => {
                    console.log('ServiceWorker registrado con éxito en index:', registration.scope);
                })
                .catch(err => {
                    console.log('Fallo en el registro del ServiceWorker en index:', err);
                });
        }

        // 2. Lógica para el botón de instalación
        let deferredPrompt;
        const seccionInstalar = document.getElementById('seccion-instalar');
        const btnInstalar = document.getElementById('btn-instalar');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Previene que Chrome muestre el mini-infobar
            e.preventDefault();
            // Guarda el evento para poder lanzarlo más tarde
            deferredPrompt = e;
            // Muestra nuestra UI de instalación personalizada
            seccionInstalar.style.display = 'block';
        });

        btnInstalar.addEventListener('click', async () => {
            // Oculta nuestra UI, ya que el prompt se va a mostrar
            seccionInstalar.style.display = 'none';
            // Muestra el prompt de instalación
            deferredPrompt.prompt();
            // Espera a que el usuario responda
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`Respuesta del usuario al prompt de instalación: ${outcome}`);
            // Ya no podemos usar el prompt, lo descartamos
            deferredPrompt = null;
        });

        window.addEventListener('appinstalled', () => {
            // Oculta la UI de instalación si la app ya se instaló
            seccionInstalar.style.display = 'none';
            deferredPrompt = null;
            console.log('PWA instalada con éxito');
        });
    });
</script>

</div>
</body>
</html>

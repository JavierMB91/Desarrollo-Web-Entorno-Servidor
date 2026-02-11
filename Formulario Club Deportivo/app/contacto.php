<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Contacto"; ?>
    <?php include 'head.php'; ?>
</head>
<body class="contacto-body">
    <div class="container">
    <header>
        <div class="titulo-con-logo">
            <h1 class="titulo-club">Contacto</h1>
        </div>
        <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
    </header>
    <main>
    <form action="" method="post" id="formularioContacto">
        <div class="bloque-form">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">
            <span id="nombreError" class="error"></span>
        </div>

        <div class="bloque-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
            <span id="emailError" class="error"></span>
        </div>

        <div class="bloque-form">
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje"></textarea>
            <span id="mensajeError" class="error"></span>
        </div>

        <div class="contenedor-botones">
            <button type="submit"><span>Enviar</span></button>
            <a href="index.php" class="btn-atras"><span>Volver</span></a>
        </div>
    </form>
    </main>
    <div id="footer"></div>

    <script src="js/funcionesContacto.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/transiciones.js"></script>
    </div>
</body>
</html>

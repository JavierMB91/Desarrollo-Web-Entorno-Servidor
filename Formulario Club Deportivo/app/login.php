<?php
session_start();
require_once 'check_sesion.php';
if (is_loged_in()) {
    header('Location: index.php');
    exit();
}

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
    <title>Login</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
    <div class="titulo-con-logo">
        <h1 class="titulo-club">Iniciar Sesion</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
    </header>

    <main>
    <form action="iniciar_sesion.php" method="post" enctype="multipart/form-data" id="formularioLogin">

    <div class="bloque-form">
        <label for="nombre">Nombre de nombre</label>
        <input id="nombre" type="text" name="nombre" placeholder="Nombre">
        <span id="nombreError" class="error"></span>
    </div>

    <div class="bloque-form">
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" placeholder="Contraseña">
        <span id="passwordError" class="error"></span>
    </div>

    <div class="contenedor-botones">
        <button type="submit"><span>Entrar</span></button>
        <a href="index.php" class="btn-atras"><span>Volver</span></a>
    </div>

    </form>
    </main>


    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">❌ Nombre o contraseña incorrectos</p>
    <?php endif; ?>
    
    <div id="footer"></div>

    <script src="js/login.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/transiciones.js"></script>
</body>
</html>
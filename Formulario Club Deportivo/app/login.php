<?php
session_start();
require_once 'check_sesion.php';
if (is_loged_in()) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Iniciar Sesión"; ?>
    <?php include 'head.php'; ?>
</head>
<body>
    <header>
        <div class="titulo-con-logo">
            <h1 class="titulo-principal">Iniciar Sesión</h1>
        </div>
        <?php include 'nav.php'; ?>
    </header>

    <main>
        <form action="iniciar_sesion.php" method="post" enctype="multipart/form-data" id="formularioLogin">

            <div class="bloque-form">
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" name="telefono" placeholder="Teléfono" value="">
            </div>

            <div class="bloque-form">
                <label for="password">Clave</label>
                <input id="password" type="password" name="password" placeholder="Clave">
            </div>

            <?php
            // Mostrar mensaje general de error
            if (isset($_GET['error'])) {
                echo '<p class="error" style="text-align:center; margin-top:10px;">❌ Teléfono o clave incorrecta</p>';
            }
            ?>

            <div class="contenedor-botones">
                <button type="submit"><span>Entrar</span></button>
                <a href="index.php" class="btn-atras"><span>Volver</span></a>
            </div>


        </form>
    </main>

    <div id="footer"></div>

    <script src="js/footer.js"></script>
    <script src="js/transiciones.js"></script>
</body>
</html>

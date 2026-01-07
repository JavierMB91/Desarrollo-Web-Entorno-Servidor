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
    <title>Login</title>
</head>
<body>
    <form action="iniciar_sesion.php" method="post">
        <div>
            <label for="usuario">Nombre de usuario: </label>
            <input type="text" name="usuario" placeholder="Usuario">
        </div>
        <div>
            <label for="password">Contraseña: </label>
            <input type="password" name="password" placeholder="Contraseña">
        </div>
        <button type="submit">Entrar</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">❌ Usuario o contraseña incorrectos</p>
    <?php endif; ?>


</body>
</html>
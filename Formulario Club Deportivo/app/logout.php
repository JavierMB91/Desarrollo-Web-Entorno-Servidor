<?php
session_start();
$_SESSION = [];        // Limpiar variables de sesión
session_destroy();     // Destruir la sesión
header('Location: index.php'); // Redirigir al inicio
exit();
?>

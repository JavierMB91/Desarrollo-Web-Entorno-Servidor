<?php
    include_once('datos.php');
    include_once('funciones.php');
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema6 - Ejercicio 4</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Cuestionario</h1>
    <?php
        //comprobar si ya se ha contestado al formulario
        if(count($_POST) > 1) {
            mostrar_resultado($preguntas);
        }else{
            mostrar_test($preguntas);
        }
    ?>
</body>
</html>
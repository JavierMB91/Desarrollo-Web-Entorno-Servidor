<?php
    //llamamos a helper.php que es donde esta la funcion
    include_once("helper.php");

    $mensaje = "";



    if(isset($_POST["nombre"]) && isset($_POST["password"]) && isset($_POST["edad"])){
        $credencialesCorrectas = comprobarCredenciales($_POST["nombre"], $_POST["password"]);
        if($credencialesCorrectas && $_POST["edad"] >= 18){
            $mensaje = "Bienvenido $_POST[nombre] te has logueado correctamente";
        }else{
            $mensaje = "Acceso denegado";
    }
    }else{
            $mensaje = "Datos insuficientes";
    }
 ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logueado</title>
</head>
<body>
    <h1>Página de entrada</h1>
    <h2>
        <?php echo $mensaje;?>
    </h2>
</body>
</html>
<?php

        $nombreUsuario = "Antonia";
        $clave = "1234";
        $mensaje = "";


//         //datos del formulario
//         $nombre = trim($_POST["nombre"]);
//         $password = $_POST["password"];

//         //comprobar si los datos que ha introducido el usuario son correctos
//         if($nombreUsuario == $nombre && $clave == $password){
//             $mensaje = "Bienvenido $nombreUsuario te has logueado correctamente";
//         }else{
//             $mensaje = "Usuario o contraseña incorrecto";
//         }
//isset es una funcion para comprobar si los datos existen
    if(isset($_POST["nombre"]) && isset($_POST["password"])){
        $nombre = trim($_POST["nombre"]);
        if($nombre == $nombreUsuario && $_POST["password"] == $clave){
            $mensaje = "Bienvenido $nombreUsuario te has logueado correctamente";
        }else{
            $mensaje = "Usuario o contraseña incorrecto";
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
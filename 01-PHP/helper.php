<?php
            //funcion que comprueba el nombre y la contraseña
        function comprobarCredenciales($nombre, $clave){
            //TODO: llamo a la base de datos para que me devuelva el nombre y la clave del usuario
            $nombreUsuario ="Antonia";
            $claveGuardada = "1234";

            $nombre = trim($nombre);
            $clave = trim($clave);

            return $nombreUsuario == $nombre && $claveGuardada == $clave;
        }
?>
<?php
//funciones
function saludar(){
    echo "Hola";
}

function saludar2($nombre){
    echo "Hola $nombre";
}

function saludar3($nombre){
    return "Hola $nombre";
}

saludar();
echo "</br>";
saludar2("Pepe");
echo "</br>";
echo(saludar3("Juan"));
echo "</br>";
echo date("H:i:s");


//especificar el tipo de dato de los parametros y el tipo de dato de retorno
/**
 * Saluda a la persona con el nombre proporcionado.
 *
 * @param string $nombre Nombre de la persona a saludar.
 * @return string Mensaje de saludo personalizado.
 */
function saludar4(string $nombre):string{
    return "Hola $nombre";
}

function sumar(int $n1, int $n2):int{
    return $n1 + $n2;
}
echo saludar4(true);

$resultado = sumar("hola", "adios");
?>
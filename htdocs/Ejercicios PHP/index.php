<?php
//ejercicio 1
echo ("<h1>Hola Mundo</h1>");

$kilometros = 834.4;
$combustible = 70;

$consumoMedio = $kilometros / $combustible;
echo("Kilómetros recorridos= " . $kilometros);
echo("</br>");
echo("Combustible gastado = " . $combustible);
echo("</br>");
echo("Consumo medio = " . $consumoMedio);
echo("</br>");
?>


<?php
//ejercicio 2
echo("</br><h1>Pasamos de Fahrenheit a Celsius </h1></br>");
$gradosFahrenheit = 75;
$gradosCelsius = 5*($gradosFahrenheit - 32)/9;

echo("Los grados Celsius son $gradosCelsius ºC");

echo("</br>");

?>


<?php
echo("</br><h1>Array de frutas</h1></br>");
echo("</br>");
$frutas = ["pera", "manzana", "albaricoque"];
var_dump($frutas);
echo("</br>");
echo($frutas[0]);
echo("</br>");

//recorrer array con bucle for (primero donde quiero iniciar, segundo condicion y tercero darle valor a i y sumarle cuanto quieres que vaya avanzando el bucle)
for($i=0 ; $i < count($frutas) ; $i = $i + 1){
    echo($frutas[$i]);
    echo("</br>");
}

//recorrer el array con bucle foreach
foreach($frutas as $item){
    echo($item . "</br>");
}

//muestra por pantalla los numeros pares del 0 al 10
for($i = 0; $i < 11 ; $i = $i + 2){
     echo($i . " ");
}

echo("</br>");

//muestra los numeros pares del siguiente array
$numeros = [4, 7, 3, 2, 89, 10];
foreach($numeros as $item){
    //comprobar que $item es un numero par
    //si quisieramos ver si es impar podemos cambiar el == por !=
    if($item % 2 == 0){
        //es numero par
        echo($item . "</br>");
    }
}

//Ejercicio 3
echo("</br>");
$x = 4;
$y = 5;
$z = 2;
$valores = [$x, $y, $z, $x+$y, $y*$z, $x/$z, $x+$y+$z,($y+$z)/$x];
echo "<table>";
foreach($valores as $indice => $valor){
    echo($valor . "</br>");
echo "
    <tr>
        <td bgcolor='#f372f3ff'>Posicion $indice</td>
        <td bgcolor='#D3D3D3'>$valor</td>
    </tr>";
}
echo "</table>";

?>

<?php

//Ejercicio 4
echo("</br>");
$mascota = ["Nombre" => "Boby", "Familia" => "Perro", "Raza" => "Cunero", "Color" => "Canela", "Peso" => 4.5];

foreach($mascota as $clave => $valor){
    echo($clave . ": ".$valor . "</br>");
}

?>



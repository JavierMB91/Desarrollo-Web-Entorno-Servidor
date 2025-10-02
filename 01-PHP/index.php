<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>01 - PHP</title>
</head>
<body>
    <?php
    echo "Hola Mundo"
    ?>
    <div class="container">
        <h1>Arrays en php</h1>
        <p>Los arrays son estructuras de datos que permiten almacenar múltiples valores en una sola variable</p>
        <p>En<span>PHP</span> existen tres tipos de arrays: </p>
        <ol>
            <li>Arrays indexados</li>
            <li>Arrays asociativos</li>
            <li>Arrays multidimensionales</li>
        </ol>
        <?php
        //Ejemplo de array asociativo
            $alumno = array("nombre" => "David", "edad" => 18, "nota" => 1, "aprobado" => true);
            $estaAprobado = "No";
            if($alumno["aprobado"] == true){
                $estaAprobado = "Si";
            }
            echo "El alumno $alumno[nombre] tiene $alumno[edad] ha sacado un $alumno[nota] y $estaAprobado está aprobado";

            echo"<br>";
            //ejemplo de array multidimensional
            $alumnos = [
                ["Juan", "López", "Desarrolador"],
                ["María", "González", "Diseñadora"],
                ["Javi", "Morilla", "Millonario"],
                ["Alex", "Moya", "Culturista"],
            ];
            echo $alumnos [2][1];
            echo"<br>";
            echo"<br>";
            foreach($alumnos as $item){
                echo"El alumno $item[0] $item[1] es $item[2]";
                echo"<br>";
            }

            $contactos = ["Juan" => "123 456 789", "María" => "321 654 987", "Javi" => "741 852 963"];
            //Mostrar una tabla con la cabecera nombre y teléfono que muestre los elementos del array $contactos

        ?>

        <table border="1">
        <tr>
            <td>Nombre</td>
            <td>Teléfono</td>
        </tr>
        <?php
        foreach($contactos as $clave => $valor){           
            echo"<tr>
                <td>$clave</td>
                <td>$valor</td>
            </tr>";
        }

        //funciones para los arrays en PHP
        $numeros = [2,5,6,7,22,1,9];
        array_shift($numeros)
        //sort ordena de menos a mayor modificando el array
        sort($numeros);
        echo implode(" " , $numeros);
        //rsort ordena de mayor a menor, si el array es de strings lo ordena alfabéticamente

        //ordenar un array asociativo por sus claves
        //para ordenar por sus claves ksort
        //krsort para ordenarlos de forma viceversa
        ksort($contactos);
        echo"<br>";
        // se puede sacar la clave, valor con el foreach
        // foreach($contactos as $clave => $valor){
        //     echo $clave;
        //     echo"<br>";
        // }
        echo implode(", ", array_keys($contactos));


        ?>
    </table>
    </div>
</body>
</html>
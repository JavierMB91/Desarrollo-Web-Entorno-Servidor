<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema 4</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Arrays Multidimensionales</h1>

    <?php
        $monedas = [
            "Pesetas", 
            "Euros", 
            "Dólares"
        ];


        echo $monedas[0];

        echo '</br>';

        $monedas2 = [
            ["Pesetas", 166.386], 
            ["Dólares", 0.96]
        ];

        echo $monedas2[0][1];

        echo '</br>';

        $clientes = [
            ["Nombre" => "Juan", "Apellido" => "López", "saldo" => 300],
            ["Nombre" => "Miguel", "Apellido" => "Diaz", "saldo" => 500],
            ["Nombre" => "Santiago", "Apellido" => "Lara", "saldo" => 400],
        ];

        echo $clientes[0]["Nombre"] . " " . $clientes[0]["Apellido"] . " tiene " . $clientes[0]["saldo"] . " €";
        echo '</br>';

        echo "{$clientes[1]['Nombre']} {$clientes[1]['Apellido']} tiene {$clientes[1]['saldo']} €";

        $coches = [
            "123ABC" => [
                "marca" => "Toyota",
                "modelo" => "Corolla",
                "precio" => 18000
            ],
            "567DEF" => [
                "marca" => "Honda",
                "modelo" => "Civic",
                "precio" => 20000
            ],
            "901GHI" => [
                "marca" => "Seat",
                "modelo" => "Leon",
                "precio" => 15000
            ],
            "199POL" => [
                "marca" => "Chevrolet",
                "modelo" => "Malibu",
                "precio" => 22000
            ],
        ];

        echo '</br>';
        $matricula = "199POL";
        echo "Marca: " . $coches[$matricula]["marca"] . '</br>'; 
        echo "Modelo: " . $coches[$matricula]["modelo"] . '</br>';
        echo "Precio: " . $coches[$matricula]["precio"] . '</br>';
        //me devuelve un array con todas las matriculas
        $matriculas = array_keys($coches);

        //me devuelve un array con todos los valores
        $coches2 = array_values($coches);

        for($i = 0; $i < count($matriculas); $i++){
            echo $matriculas[$i] . '</br>';
            echo $coches2[$i]["marca"] . " ";
            echo $coches2[$i]["modelo"] . " ";
            echo $coches2[$i]["precio"] . '</br>';
        }


    ?>

    

    <h1>Ejercicio 1</h1>
<?php
    $informacion = [
        "Granada" => 150000,
        "Madrid" =>  3000000,
        "Barcelona" =>  2879200,
        "Sevilla" =>  500000,
        "Valencia" =>  1584600,
        "Tarragona" =>  485210,
    ];


    ksort($informacion);
    echo '<table class="tabla1">';
    echo '</br>';
    echo "Tabla ordenada alfabeticamente";
    foreach($informacion as $clave => $valor) {
        echo '<tr>';
        echo "<td>$clave</td>";
        echo "<td>$valor</td>";
    }

    echo '</tr>';
    echo '</table>';
    echo '<table class="tabla2">';
    asort($informacion);
    echo '</br>';
    echo "Tabla ordenada por poblacion";
    foreach($informacion as $clave => $valor) {

            echo '<tr>';
            echo "<td>$valor</td>";
            echo "<td>$clave</td>";

    }
    echo '</tr>';
    echo '</table>';


    // Ciudad con más población
    $max_poblacion = max($informacion);
    $ciudad_max = array_search($max_poblacion, $informacion);

    // Ciudad con menos población
    $min_poblacion = min($informacion);
    $ciudad_min = array_search($min_poblacion, $informacion);

    echo "<p>Ciudad con más población: $ciudad_max ($max_poblacion habitantes)</p>";
    echo "<p>Ciudad con menos población: $ciudad_min ($min_poblacion habitantes)</p>";

?>

    <h1>Ejercicio 2</h1>
<?php
    $clase = [
        "Antonio" => [
            "Notas" => [
                "Matemáticas" => 5,
                "Lengua" => 8.3,
                "Ciencias Naturales" => 9,
                "Geografía" => 7,
            ],
        ],
        "Ana" => [
            "Notas" => [
                "Matemáticas" => 8,
                "Lengua" => 7,
                "Ciencias Naturales" => 4.5,
                "Geografía" => 9,
            ],
        ],
        "Benito" => [
            "Notas" => [
                "Matemáticas" => 9,
                "Lengua" => 6.75,
                "Ciencias Naturales" => 9,
                "Geografía" => 3.1,
            ],
        ],
    ];

// 1. Crear la tabla y la cabecera
echo '<table class="tabla3" border="1">'; // border="1" ayuda a ver las celdas
echo '<tr>';
echo '<th>Alumno</th>';
echo '<th>Matemáticas</th>';
echo '<th>Lengua</th>';
echo '<th>Ciencias Naturales</th>';
echo '<th>Geografía</th>';
echo '</tr>';

// 2. Recorrer cada alumno
foreach($clase as $nombre_alumno => $datos_alumno) {
    echo '<tr>';
    
    // 3. Imprimir el nombre del alumno
    echo "<td>$nombre_alumno</td>";
    
    // 4. Acceder a las notas y imprimirlas en cada celda
    // $datos_alumno['Notas'] nos da el array de las notas de ese alumno
    echo '<td>' . $datos_alumno['Notas']['Matemáticas'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Lengua'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Ciencias Naturales'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Geografía'] . '</td>';
    
    echo '</tr>';
}

echo '</table>';

?>
</body>
</html>
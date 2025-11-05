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


function calcularMedia($datos_alumno) {
    $media = 0;
    foreach($datos_alumno as $nota){
            $media = $media + $nota;
    }
    return $media/count($datos_alumno);
}


$buscado ="Ana";
$notas = array_key_exists($buscado, $clase);

// $notas = $clase[$buscado] ?? null;


if ($notas) {
    // Mostramos solo al alumno buscado
    echo "<table class='tabla3' border='1'>";
    echo "<tr>
            <th>Alumno</th>
            <th>Matemáticas</th>
            <th>Lengua</th>
            <th>Ciencias Naturales</th>
            <th>Geografía</th>
            <th>Media</th>
          </tr>";

    echo "<tr>";
    echo "<td>$buscado</td>";
    echo "<td>" . $clase[$buscado]['Notas']['Matemáticas'] . "</td>";
    echo "<td>" . $clase[$buscado]['Notas']['Lengua'] . "</td>";
    echo "<td>" . $clase[$buscado]['Notas']['Ciencias Naturales'] . "</td>";
    echo "<td>" . $clase[$buscado]['Notas']['Geografía'] . "</td>";
    echo '<td>' . round(calcularMedia($clase[$buscado]['Notas']), 2) . '</td>';
    echo "</tr>";
    echo "</table>";

} else {
    echo "El alumno no existe";
}


echo '<table class="tabla3" border="1">';
echo '<tr>';
echo '<th>Alumno</th>';
echo '<th>Matemáticas</th>';
echo '<th>Lengua</th>';
echo '<th>Ciencias Naturales</th>';
echo '<th>Geografía</th>';
echo '<th>Media</th>';
echo '</tr>';



foreach($clase as $nombre_alumno => $datos_alumno) {
    echo '<tr>';
    echo "<td>$nombre_alumno</td>";
    
    echo '<td>' . $datos_alumno['Notas']['Matemáticas'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Lengua'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Ciencias Naturales'] . '</td>';
    echo '<td>' . $datos_alumno['Notas']['Geografía'] . '</td>';
    echo '<td>' . round(calcularMedia($datos_alumno['Notas']), 2) . '</td>';
    echo '</tr>';
}

echo '</table>';

?>

<?php

$animales = [
    [
        "Nombre" => "Pepe",
        "Peso" => 4.5,
        "Color" => "Marrón",
        "Edad" => 12
    ],
    [
        "Nombre" => "Sparky",
        "Peso" => 3,
        "Color" => "Blanco",
        "Edad" => 2
    ],
    [
        "Nombre" => "Tobby",
        "Peso" => 7.2,
        "Color" => "Beige",
        "Edad" => 8
    ],
    [
        "Nombre" => "Bigotes",
        "Peso" => 4,
        "Color" => "Negro",
        "Edad" => 9
    ],
    [
        "Nombre" => "Ricky",
        "Peso" => 0.1,
        "Color" => "Verde",
        "Edad" => 2
    ]
];

echo '</br>';
echo '<table class="tabla4" border="1">';
echo '<tr>';
echo '<th>Fila</th>';
echo '<th>Nombre</th>';
echo '<th>Peso</th>';
echo '<th>Color</th>';
echo '<th>Edad</th>';
echo '</tr>';

foreach($animales as $clave => $valor) {
    echo '<tr>';
    echo "<td>$clave</td>";

    echo '<td>' . $valor['Nombre']. '</td>';
    echo '<td>' . $valor['Peso'] . '</td>';
    echo '<td>' . $valor['Color'] . '</td>';
    echo '<td>' . $valor['Edad'] . '</td>';
    echo '</tr>';
}

echo '</br>';

foreach($animales as $nombre) {
    echo '<table class="tabla4" border="1">';
    echo '<tr>';
    echo '<th>Nombre</th>';
    echo '<td>' . $nombre["Nombre"] . '</td>';
    echo "</tr>";
}

echo '</br>';

$posicion = 3; // índice del animal que queremos buscar

// Verificamos que el índice exista en el array
$animal = $animales[$posicion] ?? null;

if ($animal) {
    echo "<table class='tabla4' border='1'>";
    echo "<tr><th>Nombre</th><th>Peso</th></tr>";
    echo "<tr>";
    echo "<td>" . $animal["Nombre"] . "</td>";
    echo "<td>" . $animal["Peso"] . "</td>";
    echo "</tr>";
    echo "</table>";
} else {
    echo "El animal en la posición $posicion no existe";
}

echo '</table>';

echo '</br>';
echo '<table class="tabla4" border="1">';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Peso</th>';
echo "</tr>";

echo '<tr>';
echo '<td>' . $animales[3]["Nombre"] . '</td>';
echo '<td>' . $animales[3]["Peso"] . '</td>';
echo '</tr>';
echo '</table>';

echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Color</th></tr>';

echo '</br>';

foreach ($animales as $animal) {
    if ($animal["Nombre"] === "Sparky") {
        echo '<tr>';
        echo '<td>' . $animal["Nombre"] . '</td>';
        echo '<td>' . $animal["Color"] . '</td>';
        echo '</tr>';
        break; // Ya encontramos a Sparky
    }
}

echo '</table>';
echo '</br>';

$mayor = 0;
$mascotaMayor = null; // Guardaremos toda la mascota

echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Edad</th></tr>';

foreach($animales as $mascota) {
    if($mascota["Edad"] > $mayor) {
        $mayor = $mascota["Edad"];
        $mascotaMayor = $mascota; // Guardamos la mascota con la edad más grande
    }
}

echo '<tr>';
echo '<td>' . $mascotaMayor["Nombre"] . '</td>';
echo '<td>' . $mascotaMayor["Edad"] . '</td>';
echo '</tr>';
echo '</table>';

echo '</br>';

$menorPeso = 100;
$mascotaMenor = null; // Guardamos toda la mascota con menor peso

echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Peso</th></tr>';

foreach($animales as $mascota) {
    if($mascota["Peso"] < $menorPeso) {
        $menorPeso = $mascota["Peso"];
        $mascotaMenor = $mascota; // Guardamos la mascota con menor peso
    }
}

echo '<tr>';
echo '<td>' . $mascotaMenor["Nombre"] . '</td>';
echo '<td>' . $mascotaMenor["Peso"] . '</td>';
echo '</tr>';
echo '</table>';



?>
</body>
</html>
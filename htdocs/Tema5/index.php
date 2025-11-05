<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema5</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Ejercicios Tema5</h1>
    <h2>Ejercicio 1</h2>
    <?php
    function notas($juanRamirez)
    {
        echo "<table class='alumno'>";
        echo "<tr>";
        echo "<th>Alumno</th>";
        echo "<th>Juan Ramirez</th>";
        echo "</tr>";
        echo "</table>";

        foreach($juanRamirez as $asignatura => $nota) {
            echo "<table class='alumno'>";
            echo "<tr>";
            echo '<td>' . $asignatura . '</td>';
            echo '<td>' . $nota . '</td>';
            echo "</tr>";
            echo "</table>";
        }
    }

    $juanRamirez = [
        "Matematicas" => "Sobresaliente",
        "Lengua" => "Notable",
        "Historia" => "Notable",
        "Dibujo" => "Insuficiente"
    ];

    echo notas($juanRamirez);

    ?>

    <h2>Ejercicio 2</h2>

    <?php
function color($colores) {
    echo '<table class="color">';

    foreach ($colores as $nombre => $color) {
        echo "<tr>";
        echo "<td>$nombre</td>";
        echo "<td style='background-color:$color';></td>";
        echo "</tr>";
    }

    echo "</table>";
}

$colores = [
    "Rojo" => "#FF0000",
    "Amarillo" => "#FFFF00",
    "Verde" => "#2bce46ff"
];

color($colores);
?>

<h1>Ejercicio 3</h1>

<?php
function color2($colores) {
    echo '<table class="color">';

    foreach ($colores as $nombre => $colors) {
        echo "<tr class='$colors'>";
        echo "<td>$nombre</td>";
        echo "<td>$colors</td>";
        echo "</tr>";
    }

    echo "</table>";
}

$colores = [
    "Rojo" => "rojo",
    "Amarillo" => "amarillo",
    "Verde" => "verde"
];

color2($colores);
?>

<h1>Ejercicio 4 version 1</h1>

<?php

$alumno = [
    "nombre" => "Carlos",
    "apellidos" => "Gómez Pérez",
    "nota1" => 7.5,
    "nota2" => 8.0,
    "nota3" => 6.5
];

function media($alumno) {

    foreach($alumno as $clave => $valor) {
        echo $clave;
        echo $valor;
        echo "</br>";
    }
    echo " La media es " . round(($alumno['nota1'] + $alumno['nota2'] + $alumno['nota3']) / 3, 2);
}

media($alumno);
?>
<h1>Ejercicio 4 version 2</h1>
<?php

$alumno = [
    "nombre" => "Carlos",
    "apellidos" => "Gómez Pérez",
    "nota1" => 7.5,
    "nota2" => 8.0,
    "nota3" => 6.5
];

function boletinNotas($alumno) {
    // Calcular la media
    $media = round(($alumno['nota1'] + $alumno['nota2'] + $alumno['nota3']) / 3, 2);

    // Crear el HTML
    $html = "
    <div style='font-family: Arial; border: 1px solid #ccc; padding: 10px; width: 300px;'>
        <h2>Boletín de Notas</h2>
        <p><strong>Nombre:</strong> {$alumno['nombre']}</p>
        <p><strong>Apellidos:</strong> {$alumno['apellidos']}</p>
        <table border='1' cellpadding='5' cellspacing='0' width='100%'>
            <tr><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th></tr>
            <tr>
                <td>{$alumno['nota1']}</td>
                <td>{$alumno['nota2']}</td>
                <td>{$alumno['nota3']}</td>
            </tr>
        </table>
        <p><strong>Nota Final:</strong> $media</p>
    </div>";

    // Devolver el HTML
    return $html;
}

// Mostrar el boletín
echo boletinNotas($alumno);

?>

</body>
    
</html>
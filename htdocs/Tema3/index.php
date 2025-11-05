<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Tema 3</title>
</head>
<body>
    <h1>Ejercicios Tema 3</h1>

    <h2>Tablas de multiplicar</h2>
    
    <?php
        echo '<p>Tabla del 7</p>';
        $num = 7;
        echo'<table class="tabla">';
        for ($i = 1 ; $i <= 10 ; $i++){
            if($i%2==0){
                echo "<tr style='background-color: green'>";
            }else{
                echo "<tr style='background-color: yellow'>";
            }
            $resultado = $num * $i;
            echo "
                    <td>$i x $num</td>
                    <td>$resultado</td>
            ";
        }
        echo '</table>';

    ?>

    <h2>Array 4 posiciones</h2>

    <?php
    $numeros = [3, 8, 7, -6];
    echo '<table class="tabla">';
    echo '<tr>
                <td>Número</td>
                <td>Cuadrado</td>
                <td>Cubo</td>
            </tr>';

        foreach($numeros as $item){
            echo '<tr>';
                echo '<td>'. $item .'</td>';
                echo '<td>'. $item ** 2 .'</td>';
                echo '<td>'. $item ** 3 .'</td>';
            echo '</tr>';
        }

        echo '</table>'
    ?>

    <h2>Ejercicio 3</h2>
    <?php
        $alumnos = ["Alejandro" => 7, "Javier" => 4, "David" => 8, "Buendia" => 10, "Chema" => 3, "Ivan" => 2];

        echo '<table class="tabla">';

        foreach($alumnos as $clave => $valor) {
            echo '<tr>';
            echo '<td>' . $clave . '</td>';
            echo '<td>' . $valor . '</td>';

           switch($valor) {
            case ($valor<5):
                echo '<td>Suspenso</td>';
                break;
            case ($valor<6):
                echo '<td>Bien</td>';
                break;
            case ($valor>9):
                echo '<td>Notable</td>';
                break;
            case ($valor<9):
                echo '<td>Sobresaliente</td>';
                break;
            case ($valor<10):
                echo '<td>Matricula de Honor</td>';
                break;
            default:
                echo '<td>Sin nota</td>';
           }

            echo '</tr>';
        }
        echo '</table>';
    ?>

    <h2>Calendario</h2>
    <?php
    $meses = ["Enero" => 31, "Febrero" => 28, "Marzo" => 31, "Abril" => 30, "Mayo" => 31, "Junio" => 30, "Julio" => 31, "Agosto" => 31, "Septiembre" => 30, "Octubre" => 31, "Noviembre" => 30, "Diciembre" => 31];
        $diasSemnana = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado", "Domingo"];
        $dia = 3;

        foreach($meses as $mes => $dias) {
            //muestro los dias de la semana
            echo "<p>$mes</p>";
            echo '<table class="dia">';
            echo '<tr>';
            foreach($diasSemnana as $semana){
                echo "<td>$semana</td>";
            }

            echo '</tr>';
            echo '<tr>';
            //crear dias vacios de principios de mes
            for($j=1 ; $j < $dia ; $j++){
                echo '<td></td>';
            }
            //mostrar los dias del mes
            for($i = 1; $i <= $dias ; $i++) {
                echo "<td>$i</td>";
                $dia++;
                //si hemos llegado al domingo (dia 7), volvemos al lunes y cambiamos fila
                if($dia > 7) {
                    $dia = 1;
                    echo "</tr><tr>";
                }

            }
            
            echo "</tr>";
            echo '</table>';
        } 
    ?>

    <script src="js/funciones.js"></script>
</body>
</html>
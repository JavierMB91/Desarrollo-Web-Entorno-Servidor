<?php

    echo 'mostrar tabla ejercicio2';
    echo '</br>';
    $numero = $_GET['tabla'];
    $color = $_GET['color'];

    echo "<table border='1' style='color:$color;'>";

    for($i = 1; $i <= 10; $i++) {
    echo "<tr><td>$numero x $i</td><td>" . ($numero * $i) . "</td></tr>";
}

    echo "</table>";
?>
<?php
    echo 'Mostrar Tabla';
    echo '</br>';
    $numero = $_GET['numero'];
    for($i = 1; $i <= 10; $i++) {
    echo "$numero x $i = " . ($numero * $i) . "</br>" ;
    }
?>
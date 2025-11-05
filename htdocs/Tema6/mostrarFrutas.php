<?php
    echo '<h1>Frutas seleccionadas</h1>';

    foreach($_POST as $clave => $valor) {
        echo "<p>$valor</p>";
    }
?>
<?php
function mostrarCuestionario($cuestionario) {
    echo "<h1>Cuestionario de Fútbol</h1>";

    foreach ($cuestionario as $indice => $pregunta) {
        echo "<div style='margin-bottom: 15px;'>";
        echo "<strong>" . ($indice + 1) . ". " . $pregunta["pregunta"] . "</strong><br>";

        foreach ($pregunta["respuestas"] as $letra => $respuesta) {
            echo "<label>";
            echo "<input type='radio' name='pregunta$indice' value='$letra'> $letra) $respuesta";
            echo "</label><br>";
        }
        echo "</div>";
    }

    echo "<button type='submit'>Enviar</button>";
}

function corregirCuestionario($cuestionario, $respuestasUsuario) {
    $aciertos = 0;

    foreach ($cuestionario as $indice => $pregunta) {
        if (isset($respuestasUsuario["pregunta$indice"]) &&
            $respuestasUsuario["pregunta$indice"] == $pregunta["correcta"]) {
            $aciertos++;
        }
    }

    return $aciertos;
}
?>

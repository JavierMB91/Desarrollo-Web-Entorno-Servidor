<?php
    function mostrar_test($preguntas) {
    ?>
    <form action="" method="POST">
        <?php foreach($preguntas as $num => $pregunta): ?>
            <div class="pregunta">
                <h3><?php echo $num . ' ' . $pregunta['pregunta']?></h3>
                <?php foreach($pregunta ['opciones'] as $letra => $opcion): ?>
                    <?php $id = "pregunta_{$num}_{$letra}"; ?>
                    <div class="opciones">
                        <input
                        type="radio"
                        name="pregunta_<?php echo $num; ?>"
                        value="<?php echo $letra;?>"
                        id="<?php echo $id; ?>"
                        required
                        >
                         <label for="<?php echo $id; ?>">
                            <?php echo $opcion;?>
                         </label>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endforeach; ?>
        <br>
        <button type="submit" name="enviar">Enviar Respuestas</button>
    </form>
    <?php
}

function mostrar_resultado($preguntas) {

    $nota = 0;
    $errores = [];


    foreach($preguntas as $num => $pregunta) {
        if($_POST['pregunta_' . $num] === $pregunta['correcta']){
            $nota++;
        }else{
            $errores[$num] = [
                'respuesta_usuario' => $_POST['pregunta_' . $num],
                'respuesta_correcta' => $pregunta['correcta'],
                'explicación' => $pregunta['explicación']
            ];
        }
    }
    ?>

    <div class="banner-final">
    <h3>🍺 Has completado el TEST 🍺</h3>
    <div class="nota">
        <strong class="<?php echo $nota >= 5 ? 'aprobado' : 'suspenso'; ?>">
            <?php echo $nota >= 5 ? '¡Aprobado!' : 'Suspenso'; ?>
        </strong>
        <p>Tu nota final ha sido <?php echo $nota; ?> / 10</p>
    </div>

    <?php
        if(!empty($errores)) { ?>
            <div class="errores">
                <h3>Respuestas incorrectas</h3>
                <?php foreach($errores as $numPregunta => $error){
                    $respuesta_correcta = $error['respuesta_correcta'];
                    $explicacion = $error['explicación'];
                    echo "<p class='incorrecta'> $numPregunta Respuesta correcta $respuesta_correcta </p>";
                    echo "Explicación: $explicacion";

                } ?>
            </div>
    <?php   } ?>

    <a href="">Volver a intentarlo</a>
    </div>

    <?php
}
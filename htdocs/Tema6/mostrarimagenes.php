<?php

$_FILES['imagen1'];
$_FILES['imagen2'];
var_dump($_FILES['imagen1']);

//comprobar que la imagen se ha subido y que no ha habido errores
if(isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] === UPLOAD_ERR_OK) {
    // extraigo el tipo de archivo
    $tipoImagen1 = $_FILES['imagen1']['type'];
    //extraigo el tamaño
    $sizeImagen1 = $_FILES['imagen1']['size'];
    //compruebo si es una imagen
    $imagen1 = strpos($tipoImagen1, 'image/') === 0;
    //nombre temporal de la imagen
    $nombreImagen1 = $_FILES['imagen1']['tmp_name'];
}

//comprobar que la imagen se ha subido y que no ha habido errores
if(isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] === UPLOAD_ERR_OK) {
    // extraigo el tipo de archivo
    $tipoImagen2 = $_FILES['imagen2']['type'];
    //extraigo el tamaño
    $sizeImagen2 = $_FILES['imagen2']['size'];
    //compruebo si es una imagen
    $imagen2 = strpos($tipoImagen2, 'image/') === 0;
    $nombreImagen2 = $_FILES['imagen2']['tmp_name'];
}

function mostrarImagenes($nombreImagen1, $tipoImagen1, $nombreImagen2, $tipoImagen2) {
     //mostrar las imagenes
        $data1 = base64_encode(file_get_contents($nombreImagen1));
        echo "<img src='data:$tipoImagen1;base64,$data1' alt='Imagen subida' style='max-width:300px;'>";
        $data2 = base64_encode(file_get_contents($nombreImagen2));
        echo "<img src='data:$tipoImagen2;base64,$data2' alt='Imagen subida' style='max-width:300px;'>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 5</title>
</head>
<body>
    <h1>Mostrar imágenes</h1>

    <?php
    
    if($imagen1 && $imagen2){
            echo '<p>Las imágenes se han subido correctamente</p>';
            mostrarImagenes($nombreImagen1, $tipoImagen1, $nombreImagen2, $tipoImagen2);
           
    }else{
        echo '<p>UPS! Ha habido un error... Comprueba el tipo de archivo y tu conexión</p>';
        echo '<a href="index5.html">Volver al formulario</a>';
    }
    ?>
</body>
</html>
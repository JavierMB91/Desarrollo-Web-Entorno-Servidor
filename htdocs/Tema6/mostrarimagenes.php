<?php

$imagenes = ['imagen1', 'imagen2'];

$imagen = [];

foreach($imagenes as $archivos) {
        //comprobar que la imagen se ha subido y que no ha habido errores
        if(isset($_FILES[$archivos]) && $_FILES[$archivos]['error'] === UPLOAD_ERR_OK) {
        // extraigo el tipo de archivo
        $tipoImagen = $_FILES[$archivos]['type'];
        // //extraigo el tamaño
        // $sizeImagen = $_FILES[$archivos]['size'];
        //compruebo si es una imagen
        $esImagen = strpos($tipoImagen, 'image/') === 0;
       

        if($esImagen) {
            $imagen[$archivos] = [
                'nombre' => $_FILES[$archivos]['name'],
                'tipo' => $tipoImagen,
                'tamano' => $_FILES[$archivos]['size'],
                'tmp_name' => $_FILES[$archivos]['tmp_name']
            ];
        }
    }
}




function mostrarImagenes($archivos) {
     //mostrar las imagenes
        $data = base64_encode(file_get_contents($archivos));
        echo "<img src='data:$archivos;base64,$data' alt='Imagen subida' style='max-width:300px;'>";
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
    
    if ($imagen) {
        echo '<p>Las imágenes se han subido correctamente</p>';

        foreach ($imagen as $img) {
            mostrarImagenes($img['tmp_name'], $img['tipo']);
        }
           
    }else{
        echo '<p>UPS! Ha habido un error... Comprueba el tipo de archivo y tu conexión</p>';
        echo '<a href="index5.html">Volver al formulario</a>';
    }
    ?>
</body>
</html>
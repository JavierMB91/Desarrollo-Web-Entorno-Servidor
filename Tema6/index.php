<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema 6 - Ejercicio 1</title>
</head>
<body>
    <form action="mostrarTabla.php" method ="GET">
        <!-- Diferencia entre GET y POST, GET se muestra en la barra y POST en el body, mejor POST para cosas grandes -->
        <label for="numero">Introduce un numero</label>
        <input type="number" id="numero" name="numero">
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema 6 - Ejercicio 3</title>
</head>
<body>
    <h1>Selecciona tus frutas favoritas</h1>
    <form action="mostrarFrutas.php" method="POST">
        <input type="checkbox" name="manzana" id="manzana" value="Manzana">
        <label for="manzana">Manzana</label>
        <br>
        <input type="checkbox" name="pera" id="pera" value="Pera">
        <label for="pera">Pera</label>
        <br>
        <input type="checkbox" name="sandia" id="sandia" value="Sandia">
        <label for="sandia">Sandia</label>
        <br>
        <input type="checkbox" name="melocoton" id="melocoton" value="Melocoton">
        <label for="melocoton">Melocoton</label>
        <br>
        <input type="checkbox" name="platano" id="platano" value="Platano">
        <label for="platano">Platano</label>
        <br>
        <input type="checkbox" name="melon" id="melon" value="Melon">
        <label for="melon">Melon</label>
        <br>
        <input type="checkbox" name="fresa" id="fresa" value="Fresa">
        <label for="fresa">Fresa</label>
        <br>
        <input type="checkbox" name="albaricoque" id="albaricoque" value="Albaricoque">
        <label for="albaricoque">Albaricoque</label>
        <br>
        <br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
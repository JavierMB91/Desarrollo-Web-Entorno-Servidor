<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="comprobarLogin.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre">

        <label for="password">Password:</label>
        <input type="password" name="password">



        <input type="radio" name="colores" value="azul"> <label for="">Azúl</label>
        <input type="radio" name="colores" value="verde"> <label for="">Verde</label>
        <input type="radio" name="colores" value="amarillo"> <label for="">Amarillo</label>


        <input type="checkbox" name="acepta" id=""><label for="">Acepta politicas de privacidad</label>

        <input type="date" name="fecha" id="">

        <input type="datetime-local" name="fechahora" id="">

        <input type="color" name="color" id="">
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
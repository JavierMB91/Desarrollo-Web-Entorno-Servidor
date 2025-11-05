<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema 6 - Ejercicio 2</title>
</head>
<body>
    <h1>Tabla de multiplicar de colores</h1>
    <form action="mostrarTabla2.php">
        <label for="tabla">Selecciona la tabla</label>
        <input list="opciones" id="tabla" name="tabla">
        <datalist id="opciones">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </datalist>
            <input type="radio" name="color" value="red">Rojo
            <input type="radio" name="color" value="yellow">Amarillo
            <input type="radio" name="color" value="green">Verde
        
        
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
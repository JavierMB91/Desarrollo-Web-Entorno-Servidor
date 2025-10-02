<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="comprobarLogin2.php" method="POST">
        <label for="nombre">Nombre</label>
        <input name="nombre" type="text" placeholder="Escribe tu nombre" required>

        <label for="password">Password</label>
        <input name="password" type="password" placeholder="Escribe tu contraseña" required>
        
        <label for="edad">Edad</label>
        <input name="edad" type="number" placeholder="Escriba tu edad" required>

        <button type="submit">Enviar</button>
    </form>
    
</body>
</html>
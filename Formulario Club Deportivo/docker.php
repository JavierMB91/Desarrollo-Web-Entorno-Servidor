<?php
require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Aplicación Web con Docker</h1>

        <div id="connection-status">
            <?php
                // Ejemplo: Mostrar datos de una tabla
                $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 5");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Usuarios registrados:</h2>';
                    echo '<ul id="user-list">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li>' . htmlspecialchars($row['nombre']) . '</li>';
                    }
                    echo '</ul>';
                }
            ?>
        </div>

        <button id="test-button">Probar JavaScript</button>
        <div id="message"></div>
    </div>

    <script src="js/script.js"></script>
</body>

</html>
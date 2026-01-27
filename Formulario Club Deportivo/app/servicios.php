<?php
session_start();
require_once 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="shortcut icon" href="favicon/favicon.ico">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Lista Servicios</title>
</head>
<body class="index-body">
    
    <!-- ======================== -->
    <!-- ESTADO DE CONEXIÓN -->
    <!-- ======================== -->
    <div id="connection-status">
        <?php
            // ========================
            // 1. Búsqueda
            // ========================
            $busqueda = $_GET['q'] ?? '';

            $sql = "SELECT * FROM servicio 
                    WHERE nombre LIKE :q";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['q' => "%$busqueda%"]);
        ?>
    </div>

    <!-- ======================== -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ======================== -->
    <div class="container">

    <!-- ======================== -->
    <!-- HEADER -->
    <!-- ======================== -->
    <header>
        <div class="titulo-con-logo">
            <h1 class="titulo-club">Actividades</h1>
        </div>
        <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
    </header>

        <!-- ======================== -->
        <!-- BUSCADOR + BOTONES -->
        <!-- ======================== -->
        <main>
            <h2 class="titulo-club">Listado de Actividades</h2>
            <form method="get" action="servicios.php">
                <input type="text" name="q" 
                       placeholder="Buscar por nombre de actividad"
                       value="<?= htmlspecialchars($busqueda) ?>">

                <div class="contenedor-botones">
                    <button type="submit"><span>Buscar</span></button>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                        <a href="servicio.php" class="btn-atras"><span>Agregar nueva Actividad</span></a>
                    <?php endif; ?>
                    <a href="servicios.php" class="btn-atras"><span>Mostrar todas</span></a>
                </div>
            </form>
        </main>

        

        <!-- ======================== -->
        <!-- LISTADO DE SERVICIOS -->
        <!-- ======================== -->
        <div class="servicios-lista">
            <?php
            if ($stmt->rowCount() > 0) {

                while ($servicio = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    
                    echo '<div class="servicio-card">';

                    // DATOS
                    echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($servicio['nombre']) . '</p>';
                    echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($servicio['descripcion']) . '</p>';
                    echo '<p><strong>Duración:</strong> ' . htmlspecialchars($servicio['duracion']) . ' min</p>';

                    // Formatear precio: mostrar 'Gratuito' si es 0, o formato con 2 decimales y símbolo €
                    if (floatval($servicio['precio']) == 0) {
                        $precioMostrar = 'Gratuito';
                    } else {
                        $precioMostrar = number_format(floatval($servicio['precio']), 2, ',', '.') . ' €';
                    }
                    echo '<p><strong>Precio:</strong> ' . htmlspecialchars($precioMostrar) . '</p>';


                    // 🔥 NUEVO: MOSTRAR HORA
                    // No mostrar la hora en el listado (el usuario solicitó eliminarla)

                    // BOTÓN EDITAR
                    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
                        echo '<div class="contenedor-botones">
                                <a href="editarServicio.php?id=' . $servicio['id'] . '" class="btn-atras">
                                    <span>Editar</span>
                                </a>
                              </div>';
                    }

                    echo '</div>';
                }

            } else {
                echo '<div class="resultados-busqueda">
                    <p>No se encontraron Actividades</p>
                 </div>';
            }
            ?>
        </div>

        <!-- BOTÓN Volver -->
        <div class="contenedor-botones">
            <a href="index.php" class="btn-atras"><span>Volver</span></a>
        </div>

    </div> <!-- CIERRE DEL CONTAINER -->

    <!-- ======================== -->
    <!-- FOOTER -->
    <!-- ======================== -->
    <div id="footer"></div>
    
    <script src="js/footer.js"></script>
    <script src="js/transiciones.js"></script>
    <script src="js/asistente.js" defer></script>
</body>
</html>

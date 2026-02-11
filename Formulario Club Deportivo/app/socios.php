<?php
session_start();
require_once 'conexion.php';

// ====================================================
// PROTECCIÓN DE RUTA: SOLO ADMINISTRADORES
// ====================================================

// 1. Verificar si está logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// 2. Verificar el rol en la base de datos (más seguro que confiar solo en la sesión)
$stmtAuth = $pdo->prepare("SELECT rol FROM usuario WHERE id = :id");
$stmtAuth->execute(['id' => $_SESSION['id']]);
$usuarioActual = $stmtAuth->fetch(PDO::FETCH_ASSOC);

// 3. Si no existe o no es administrador, expulsar
if (!$usuarioActual || $usuarioActual['rol'] !== 'administrador') {
    header("Location: index.php");
    exit;
}

// ====================================================
// LÓGICA DE BÚSQUEDA Y LISTADO
// ====================================================
$busqueda = $_GET['q'] ?? '';

// Buscamos por nombre o teléfono
$sql = "SELECT * FROM usuario 
        WHERE nombre LIKE :q OR telefono LIKE :q 
        ORDER BY rol ASC, nombre ASC"; // Ordenar por rol primero para ver admins arriba

$stmt = $pdo->prepare($sql);
$stmt->execute(['q' => "%$busqueda%"]);
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Gestión de Socios"; ?>
    <?php include 'head.php'; ?>
</head>
<body class="socios-body">
    <div class="container">

        <header>
            <div class="titulo-con-logo">
                <h1 class="titulo-principal">Gestión de Socios</h1>
            </div>
            <?php include 'nav.php'; ?>
        </header>

        <main>
            <!-- BUSCADOR -->
            <form method="get" action="socios.php">
                <input type="text" name="q" 
                       placeholder="Buscar por nombre o teléfono..."
                       value="<?= htmlspecialchars($busqueda) ?>">

                <div class="contenedor-botones">
                    <button type="submit"><span>Buscar</span></button>
                    <a href="socios.php" class="btn-atras"><span>Ver Todos</span></a>
                    <!-- Enlace para registrar nuevo socio manualmente si se desea -->
                    <a href="socio.php" class="btn-atras"><span>Nuevo Registro</span></a>
                </div>
            </form>

            <!-- LISTADO DE TARJETAS -->
            <div class="socios-lista">
                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($socio = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="socio-card">
                            
                            <!-- FOTO -->
                            <?php 
                                $fotoPath = !empty($socio['foto']) ? 'uploads/usuarios/' . $socio['foto'] : null;
                                $nombreSocio = htmlspecialchars($socio['nombre']);
                                if ($fotoPath && file_exists(__DIR__ . '/' . $fotoPath)) {
                                    echo '<img src="' . htmlspecialchars($fotoPath) . '" alt="Foto de perfil de ' . $nombreSocio . '" loading="lazy" decoding="async" width="150" height="150">';
                                } else {
                                    echo '<img src="https://ui-avatars.com/api/?name=' . urlencode($socio['nombre']) . '&background=random" alt="Avatar de ' . $nombreSocio . '" loading="lazy" decoding="async" width="150" height="150">';
                                }
                            ?>

                            <!-- DATOS -->
                            <h3><?= htmlspecialchars($socio['nombre']) ?></h3>
                            <p><strong>Rol:</strong> <?= ucfirst(htmlspecialchars($socio['rol'])) ?></p>
                            <p><strong>Edad:</strong> <?= htmlspecialchars($socio['edad']) ?> anos</p>
                            <p><strong>Tel:</strong> <?= htmlspecialchars($socio['telefono']) ?></p>

                            <!-- BOTONES DE ACCIÓN -->
                            <div class="contenedor-botones">
                                <a href="editarSocio.php?id=<?= $socio['id'] ?>">Editar</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="resultados-busqueda">
                        <p>No se encontraron socios con ese criterio.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <div id="footer"></div>
        <script src="js/footer.js?v=<?= filemtime('js/footer.js') ?>"></script>
        <script>
            // Registrar el Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('sw.js')
                        .then(registration => {
                            console.log('ServiceWorker registrado con éxito:', registration.scope);
                        })
                        .catch(err => {
                            console.log('Fallo en el registro del ServiceWorker:', err);
                        });
                });
            }
        </script>
    </div>
</body>
</html>
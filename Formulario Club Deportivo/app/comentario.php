<?php
session_start();
require_once 'conexion.php';

// ==========================
// OBTENER TODOS LOS TESTIMONIOS
// ==========================
$sql = "SELECT 
            t.id AS testimonio_id, 
            u.nombre AS autor, 
            t.contenido, 
            t.fecha
        FROM testimonio t
        JOIN usuario u ON t.autor_id = u.id
        ORDER BY t.fecha DESC";

$stmt = $pdo->query($sql);
$testimonios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php $tituloPagina = "Lista Comentarios"; ?>
    <?php include 'head.php'; ?>
</head>

<body class="socios-body">
<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-principal">Comentarios</h1>
    </div>
    <?php include 'nav.php'; ?> <!-- Antes era nav.html -->
</header>

<main>

<!-- =======================
     MENSAJES DE SESIÓN
======================= -->
<?php if (isset($_SESSION['mensaje_exito'])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])): ?>
    <div class="mensaje-error">
        <?= $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['id']) && in_array($_SESSION['rol'] ?? '', ['administrador', 'socio'])): ?>
    <div class="contenedor-botones">
        <a class="btn-atras" href="testimonio.php"><span>Nuevo Comentario</span></a>
    </div>
<?php endif; ?>

<h2 class="titulo-principal">Todos los comentarios</h2>

<div class="testimonios-lista">
    <?php if (empty($testimonios)): ?>
                <div class="resultados-busqueda">
                    <p>No se encontraron comentarios</p>
                 </div>
    <?php else: ?>
        <?php foreach ($testimonios as $t): ?>
            <div class="tarjeta-testimonio">
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                    <p><strong>ID:</strong> <?= $t["testimonio_id"] ?></p>
                <?php endif; ?>
                <p><strong>Autor:</strong> <?= htmlspecialchars($t["autor"]) ?></p>
                <p class="comentario"><strong>Comentario:</strong> <?= nl2br(htmlspecialchars($t["contenido"])) ?></p>
                <p class="fecha"><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($t["fecha"])) ?></p>
                <p class="hora"><strong>Hora:</strong> <?= date('H:i', strtotime($t["fecha"])) . ' ' . date('a', strtotime($t["fecha"])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="contenedor-botones">
    <a href="index.php" class="btn-atras"><span>Volver</span></a>
</div>

</main>

<div id="footer"></div>
<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</div>
</body>
</html>

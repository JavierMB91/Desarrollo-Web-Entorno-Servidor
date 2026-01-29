<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado para ver las noticias
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Cantidad de noticias por página
$porPagina = 4;

// Determinar la página actual
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

// Calcular desde qué registro empezamos
$inicio = ($pagina - 1) * $porPagina;

// Contar cuántas noticias hay en total
$sqlTotal = "SELECT COUNT(*) FROM noticia WHERE fecha_publicacion <= NOW()";
$totalNoticias = $pdo->query($sqlTotal)->fetchColumn();

// Calcular total de páginas
$totalPaginas = ceil($totalNoticias / $porPagina);

// Obtener las noticias de la página actual
$sql = "SELECT id, titulo, contenido, imagen, fecha_publicacion
    FROM noticia
    WHERE fecha_publicacion <= NOW()
    ORDER BY fecha_publicacion DESC
    LIMIT :inicio, :porPagina";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php $tituloPagina = "Noticias"; ?>
    <?php include 'head.php'; ?>
</head>
<body class="index-body">
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

<div class="container">

<header>
    <div class="titulo-con-logo">
        <h1 class="titulo-principal">Noticias</h1>
    </div>
    <?php include 'nav.php'; ?>
</header>

<main>
    <h2 class="titulo-principal">Últimas noticias</h2>

<!-- Botón para crear noticia -->
<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
    <div class="contenedor-botones">
        <a class="btn" href="noticia.php"><span>Nueva Noticia</span></a>
    </div>
<?php endif; ?>

<section class="lista-noticias">

    <?php if (!empty($noticias)): ?>
        <div class="noticias-grid">

        <?php foreach ($noticias as $n): ?>
            <article class="noticia-card">

                <!-- Imagen -->
                <img src="uploads/noticias/<?= htmlspecialchars($n['imagen']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>" class="imagen-noticia" loading="lazy" decoding="async" width="400" height="200">


                <!-- Título -->
                <h3><?= htmlspecialchars($n['titulo']) ?></h3>

                

                <!-- Fecha -->
                <small>Publicado: <?= date("d/m/Y", strtotime($n["fecha_publicacion"])) ?></small>

                <!-- Ver noticia -->
                <a class="btn" href="verNoticia.php?id=<?= $n['id'] ?>">Leer más</a>

            </article>
        <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p>No hay noticias disponibles aún.</p>
    <?php endif; ?>
</section>

<!-- PAGINACIÓN -->
<div class="paginacion">
    <?php if ($pagina > 1): ?>
        <a class="btn" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
    <?php endif; ?>

    <span>Página <?= $pagina ?> de <?= $totalPaginas ?></span>

    <?php if ($pagina < $totalPaginas): ?>
        <a class="btn" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
    <?php endif; ?>
</div>


</main>

<div class="contenedor-botones">
    <a href="index.php" class="btn-atras"><span>Volver</span></a>
</div>

<div id="footer"></div>

<script src="js/footer.js"></script>
<script src="js/transiciones.js"></script>

</div>
</body>
</html>

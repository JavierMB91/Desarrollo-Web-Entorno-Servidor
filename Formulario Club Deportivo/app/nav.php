<nav class="navegador">
    <ul class="menu">
        <!-- Ya no ponemos session_start() aquí, asumimos que ya se inició en el archivo padre -->
        
        <?php if (isset($_SESSION['usuario'])): ?>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
        
        <li><a href="index.php">Inicio</a></li>
        <li><a href="socios.php">Listado de Socios</a></li>
        <li><a href="socio.php">Hazte Socio</a></li>
        <li><a href="servicios.php">Actividades</a></li>
        <li><a href="comentario.php">Comentarios</a></li>
        <li><a href="noticias.php">Noticias</a></li>
        <li><a href="citas.php">Agendar Actividad</a></li>
    </ul>
</nav>
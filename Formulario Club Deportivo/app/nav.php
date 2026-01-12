<nav class="navegador">
    <ul class="menu">
        <!-- Ya no ponemos session_start() aquí, asumimos que ya se inició en el archivo padre -->
        
      
        
        
            <?php if(isset($_SESSION['nombre'])): ?>
                <!-- Usuario logueado -->
                <div class="user-menu">
                    <button class="user-button" id="userMenuBtn">
                        <span class="user-icon">👤</span>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                        <span class="arrow">▼</span>
                    </button>
                    
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="perfil.php" class="dropdown-item">
                            <span class="icon">⚙️</span> Ver perfil
                        </a>
                        <a href="logout.php" class="dropdown-item">
                            <span class="icon">🚪</span> Cerrar sesión
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Usuario no logueado -->
                <a href="login.php" class="login-link">Iniciar sesión</a>
            <?php endif; ?>
        


        <li><a href="index.php">Inicio</a></li>
        
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <li><a href="socios.php">Listado de Socios</a></li>
        <?php endif; ?>

        <li><a href="socio.php">Hazte Socio</a></li>
        <li><a href="servicios.php">Actividades</a></li>
        <li><a href="comentario.php">Comentarios</a></li>
        <?php if (isset($_SESSION['id'])): ?>
            <li><a href="noticias.php">Noticias</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['id'])): ?>
            <li><a href="citas.php">Agendar Actividad</a></li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    // Toggle del menú desplegable
    const userMenuBtn = document.getElementById('userMenuBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if(userMenuBtn) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        // Cerrar el menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if(!userMenuBtn.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
</script>
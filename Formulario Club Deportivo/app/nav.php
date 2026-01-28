<nav class="navegador">
    <!-- Botón Hamburguesa -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>

    <!-- Menú Principal -->
    <ul class="menu" id="navMenu">
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

        <!-- Sección de autenticación (solo en mobile dentro del menú) -->
        <li class="auth-mobile">
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
        </li>
    </ul>

    <!-- Sección de autenticación (solo en desktop) -->
    <div class="auth-desktop">
        <?php if(isset($_SESSION['nombre'])): ?>
            <!-- Usuario logueado -->
            <div class="user-menu">
                <button class="user-button" id="userMenuBtn2">
                    <span class="user-icon">👤</span>
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                    <span class="arrow">▼</span>
                </button>
                
                <div class="dropdown-menu" id="dropdownMenu2">
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
    </div>
</nav>

<script>
    // ===== MENÚ HAMBURGUESA =====
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navMenu = document.getElementById('navMenu');
    
    hamburgerBtn.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        hamburgerBtn.classList.toggle('active');
    });
    
    // Cerrar menú al hacer click en un enlace
    const menuLinks = navMenu.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            hamburgerBtn.classList.remove('active');
        });
    });

    // ===== DROPDOWN DE USUARIO (Desktop) =====
    const userMenuBtn2 = document.getElementById('userMenuBtn2');
    const dropdownMenu2 = document.getElementById('dropdownMenu2');
    
    if(userMenuBtn2) {
        userMenuBtn2.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu2.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if(dropdownMenu2 && !userMenuBtn2.contains(e.target)) {
                dropdownMenu2.classList.remove('show');
            }
        });
    }

    // ===== DROPDOWN DE USUARIO (Mobile) =====
    const userMenuBtn = document.getElementById('userMenuBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if(userMenuBtn) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if(dropdownMenu && !userMenuBtn.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
</script>

<!-- Cargar el asistente en todas las páginas que usan la navegación -->
<script src="js/asistente.js" defer></script>
// Tibia Quiz - Animaciones y efectos temáticos
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let efectoActivo = false;
    let partículas = [];
    
    // Crear contenedor para efectos especiales
    const efectosContainer = document.createElement('div');
    efectosContainer.id = 'efectos-container';
    efectosContainer.style.position = 'fixed';
    efectosContainer.style.top = '0';
    efectosContainer.style.left = '0';
    efectosContainer.style.width = '100%';
    efectosContainer.style.height = '100%';
    efectosContainer.style.pointerEvents = 'none';
    efectosContainer.style.zIndex = '1000';
    document.body.appendChild(efectosContainer);
    
    // Función para crear partículas mágicas
    function crearPartícula(x, y, tipo = 'magia') {
        const partícula = document.createElement('div');
        partícula.className = `partícula-${tipo}`;
        
        // Estilos base para partículas
        partícula.style.position = 'absolute';
        partícula.style.left = `${x}px`;
        partícula.style.top = `${y}px`;
        partícula.style.width = '10px';
        partícula.style.height = '10px';
        partícula.style.borderRadius = '50%';
        partícula.style.pointerEvents = 'none';
        
        // Estilos específicos según tipo
        switch(tipo) {
            case 'magia':
                partícula.style.background = 'radial-gradient(circle, #9d4edd, #5a189a)';
                partícula.style.boxShadow = '0 0 10px #9d4edd';
                break;
            case 'cura':
                partícula.style.background = 'radial-gradient(circle, #52b788, #2d6a4f)';
                partícula.style.boxShadow = '0 0 10px #52b788';
                break;
            case 'fuego':
                partícula.style.background = 'radial-gradient(circle, #ff9500, #ff4800)';
                partícula.style.boxShadow = '0 0 10px #ff9500';
                break;
            case 'hielo':
                partícula.style.background = 'radial-gradient(circle, #90e0ef, #0077b6)';
                partícula.style.boxShadow = '0 0 10px #90e0ef';
                break;
            case 'muerte':
                partícula.style.background = 'radial-gradient(circle, #7209b7, #3c096c)';
                partícula.style.boxShadow = '0 0 10px #7209b7';
                break;
        }
        
        efectosContainer.appendChild(partícula);
        
        // Animación de la partícula
        const duración = 1000 + Math.random() * 2000;
        const distancia = 50 + Math.random() * 100;
        const ángulo = Math.random() * Math.PI * 2;
        const destinoX = x + Math.cos(ángulo) * distancia;
        const destinoY = y + Math.sin(ángulo) * distancia;
        
        partícula.animate([
            { 
                transform: 'translate(0, 0) scale(1)',
                opacity: 1
            },
            { 
                transform: `translate(${destinoX - x}px, ${destinoY - y}px) scale(0)`,
                opacity: 0
            }
        ], {
            duration: duración,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        }).onfinish = () => partícula.remove();
    }
    
    // Función para crear explosión de partículas
    function crearExplosión(x, y, cantidad = 20, tipo = 'magia') {
        for (let i = 0; i < cantidad; i++) {
            setTimeout(() => {
                crearPartícula(x, y, tipo);
            }, i * 30);
        }
    }
    
    // Función para crear efecto de hechizo
    function crearHechizo(x, y, destinoX, destinoY, tipo = 'magia') {
        const hechizo = document.createElement('div');
        hechizo.className = `hechizo-${tipo}`;
        
        // Estilos base para hechizo
        hechizo.style.position = 'absolute';
        hechizo.style.left = `${x}px`;
        hechizo.style.top = `${y}px`;
        hechizo.style.width = '20px';
        hechizo.style.height = '20px';
        hechizo.style.borderRadius = '50%';
        hechizo.style.pointerEvents = 'none';
        
        // Estilos específicos según tipo
        switch(tipo) {
            case 'magia':
                hechizo.style.background = 'radial-gradient(circle, #9d4edd, #5a189a)';
                hechizo.style.boxShadow = '0 0 15px #9d4edd';
                break;
            case 'cura':
                hechizo.style.background = 'radial-gradient(circle, #52b788, #2d6a4f)';
                hechizo.style.boxShadow = '0 0 15px #52b788';
                break;
            case 'fuego':
                hechizo.style.background = 'radial-gradient(circle, #ff9500, #ff4800)';
                hechizo.style.boxShadow = '0 0 15px #ff9500';
                break;
            case 'hielo':
                hechizo.style.background = 'radial-gradient(circle, #90e0ef, #0077b6)';
                hechizo.style.boxShadow = '0 0 15px #90e0ef';
                break;
            case 'muerte':
                hechizo.style.background = 'radial-gradient(circle, #7209b7, #3c096c)';
                hechizo.style.boxShadow = '0 0 15px #7209b7';
                break;
        }
        
        efectosContainer.appendChild(hechizo);
        
        // Animación del hechizo
        const duración = 500;
        
        hechizo.animate([
            { 
                transform: 'translate(0, 0) scale(1)',
                opacity: 1
            },
            { 
                transform: `translate(${destinoX - x}px, ${destinoY - y}px) scale(1.5)`,
                opacity: 0.8
            }
        ], {
            duration: duración,
            easing: 'linear'
        }).onfinish = () => {
            crearExplosión(destinoX, destinoY, 15, tipo);
            hechizo.remove();
        };
    }
    
    // Función para crear efecto de texto flotante
    function crearTextoFlotante(x, y, texto, color = '#ffcc33') {
        const textoFlotante = document.createElement('div');
        textoFlotante.className = 'texto-flotante';
        textoFlotante.textContent = texto;
        
        // Estilos para el texto flotante
        textoFlotante.style.position = 'absolute';
        textoFlotante.style.left = `${x}px`;
        textoFlotante.style.top = `${y}px`;
        textoFlotante.style.color = color;
        textoFlotante.style.fontFamily = "'MedievalSharp', cursive";
        textoFlotante.style.fontSize = '24px';
        textoFlotante.style.fontWeight = 'bold';
        textoFlotante.style.textShadow = `0 0 10px ${color}`;
        textoFlotante.style.pointerEvents = 'none';
        textoFlotante.style.zIndex = '1001';
        
        efectosContainer.appendChild(textoFlotante);
        
        // Animación del texto flotante
        textoFlotante.animate([
            { 
                transform: 'translate(0, 0) scale(0.5)',
                opacity: 0
            },
            { 
                transform: 'translate(0, -20px) scale(1.2)',
                opacity: 1
            },
            { 
                transform: 'translate(0, -50px) scale(1)',
                opacity: 0
            }
        ], {
            duration: 2000,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        }).onfinish = () => textoFlotante.remove();
    }
    
    // Función para crear efecto de nivel up
    function crearEfectoNivelUp() {
        const nivelUp = document.createElement('div');
        nivelUp.className = 'nivel-up';
        nivelUp.innerHTML = '<h2>LEVEL UP!</h2>';
        
        // Estilos para el efecto de nivel up
        nivelUp.style.position = 'fixed';
        nivelUp.style.top = '50%';
        nivelUp.style.left = '50%';
        nivelUp.style.transform = 'translate(-50%, -50%)';
        nivelUp.style.color = '#ffcc33';
        nivelUp.style.fontFamily = "'Uncial Antiqua', serif";
        nivelUp.style.fontSize = '60px';
        nivelUp.style.fontWeight = 'bold';
        nivelUp.style.textShadow = '0 0 20px #ffcc33';
        nivelUp.style.pointerEvents = 'none';
        nivelUp.style.zIndex = '2000';
        
        efectosContainer.appendChild(nivelUp);
        
        // Crear explosión de partículas alrededor del texto
        const rect = nivelUp.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        for (let i = 0; i < 50; i++) {
            const ángulo = (Math.PI * 2 * i) / 50;
            const distancia = 100;
            const x = centerX + Math.cos(ángulo) * distancia;
            const y = centerY + Math.sin(ángulo) * distancia;
            
            setTimeout(() => {
                crearHechizo(x, y, centerX, centerY, 'magia');
            }, i * 20);
        }
        
        // Animación del texto
        nivelUp.animate([
            { 
                transform: 'translate(-50%, -50%) scale(0.5)',
                opacity: 0
            },
            { 
                transform: 'translate(-50%, -50%) scale(1.2)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 0
            }
        ], {
            duration: 3000,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        }).onfinish = () => nivelUp.remove();
    }
    
    // Función para crear efecto de victoria
    function crearEfectoVictoria() {
        const victoria = document.createElement('div');
        victoria.className = 'victoria';
        victoria.innerHTML = '<h2>¡VICTORIA!</h2>';
        
        // Estilos para el efecto de victoria
        victoria.style.position = 'fixed';
        victoria.style.top = '50%';
        victoria.style.left = '50%';
        victoria.style.transform = 'translate(-50%, -50%)';
        victoria.style.color = '#5eff85';
        victoria.style.fontFamily = "'Uncial Antiqua', serif";
        victoria.style.fontSize = '60px';
        victoria.style.fontWeight = 'bold';
        victoria.style.textShadow = '0 0 20px #5eff85';
        victoria.style.pointerEvents = 'none';
        victoria.style.zIndex = '2000';
        
        efectosContainer.appendChild(victoria);
        
        // Crear explosión de partículas alrededor del texto
        const rect = victoria.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        for (let i = 0; i < 50; i++) {
            const ángulo = (Math.PI * 2 * i) / 50;
            const distancia = 100;
            const x = centerX + Math.cos(ángulo) * distancia;
            const y = centerY + Math.sin(ángulo) * distancia;
            
            setTimeout(() => {
                crearHechizo(x, y, centerX, centerY, 'cura');
            }, i * 20);
        }
        
        // Animación del texto
        victoria.animate([
            { 
                transform: 'translate(-50%, -50%) scale(0.5)',
                opacity: 0
            },
            { 
                transform: 'translate(-50%, -50%) scale(1.2)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 0
            }
        ], {
            duration: 3000,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        }).onfinish = () => victoria.remove();
    }
    
    // Función para crear efecto de derrota
    function crearEfectoDerrota() {
        const derrota = document.createElement('div');
        derrota.className = 'derrota';
        derrota.innerHTML = '<h2>¡DERROTA!</h2>';
        
        // Estilos para el efecto de derrota
        derrota.style.position = 'fixed';
        derrota.style.top = '50%';
        derrota.style.left = '50%';
        derrota.style.transform = 'translate(-50%, -50%)';
        derrota.style.color = '#ff5757';
        derrota.style.fontFamily = "'Uncial Antiqua', serif";
        derrota.style.fontSize = '60px';
        derrota.style.fontWeight = 'bold';
        derrota.style.textShadow = '0 0 20px #ff5757';
        derrota.style.pointerEvents = 'none';
        derrota.style.zIndex = '2000';
        
        efectosContainer.appendChild(derrota);
        
        // Crear explosión de partículas alrededor del texto
        const rect = derrota.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        for (let i = 0; i < 50; i++) {
            const ángulo = (Math.PI * 2 * i) / 50;
            const distancia = 100;
            const x = centerX + Math.cos(ángulo) * distancia;
            const y = centerY + Math.sin(ángulo) * distancia;
            
            setTimeout(() => {
                crearHechizo(x, y, centerX, centerY, 'muerte');
            }, i * 20);
        }
        
        // Animación del texto
        derrota.animate([
            { 
                transform: 'translate(-50%, -50%) scale(0.5)',
                opacity: 0
            },
            { 
                transform: 'translate(-50%, -50%) scale(1.2)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 1
            },
            { 
                transform: 'translate(-50%, -50%) scale(1)',
                opacity: 0
            }
        ], {
            duration: 3000,
            easing: 'cubic-bezier(0, .9, .57, 1)'
        }).onfinish = () => derrota.remove();
    }
    
    // Efectos al pasar el ratón sobre las opciones
    const opciones = document.querySelectorAll('.opciones label');
    opciones.forEach(opción => {
        opción.addEventListener('mouseenter', function(e) {
            if (efectoActivo) return;
            
            const rect = this.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            crearPartícula(x, y, 'magia');
        });
    });
    
    // Efectos al hacer clic en las opciones
    opciones.forEach(opción => {
        opción.addEventListener('click', function(e) {
            if (efectoActivo) return;
            
            const rect = this.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            crearExplosión(x, y, 10, 'magia');
            
            // Efecto de sonido (simulado)
            const audio = new Audio();
            audio.volume = 0.3;
            // En un proyecto real, aquí se cargaría un archivo de sonido
            // audio.src = 'sonidos/click.mp3';
            // audio.play();
        });
    });
    
    // Efectos al enviar el formulario
    const formulario = document.querySelector('form');
    if (formulario) {
        formulario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (efectoActivo) return;
            efectoActivo = true;
            
            // Crear efecto de carga
            const boton = this.querySelector('button[type="submit"]');
            const rect = boton.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            // Desactivar el botón
            boton.disabled = true;
            boton.textContent = 'Procesando...';
            
            // Crear efecto de hechizo desde el botón hacia arriba
            crearHechizo(x, y, window.innerWidth / 2, 100, 'magia');
            
            // Simular envío del formulario
            setTimeout(() => {
                // Enviar el formulario realmente
                this.submit();
            }, 1500);
        });
    }
    
    // Efectos en la página de resultados
    const bannerFinal = document.querySelector('.banner-final');
    if (bannerFinal) {
        const nota = parseInt(bannerFinal.querySelector('.nota p').textContent.match(/\d+/)[0]);
        
        // Crear efecto según la nota
        setTimeout(() => {
            if (nota >= 5) {
                crearEfectoVictoria();
                
                // Crear texto flotante para cada punto
                for (let i = 1; i <= nota; i++) {
                    setTimeout(() => {
                        const x = Math.random() * window.innerWidth;
                        const y = Math.random() * window.innerHeight;
                        crearTextoFlotante(x, y, '+1 EXP', '#5eff85');
                    }, i * 200);
                }
                
                // Efecto de nivel up si la nota es alta
                if (nota >= 8) {
                    setTimeout(() => {
                        crearEfectoNivelUp();
                    }, 2000);
                }
            } else {
                crearEfectoDerrota();
                
                // Crear texto flotante para cada punto perdido
                for (let i = 1; i <= (10 - nota); i++) {
                    setTimeout(() => {
                        const x = Math.random() * window.innerWidth;
                        const y = Math.random() * window.innerHeight;
                        crearTextoFlotante(x, y, '-1 HP', '#ff5757');
                    }, i * 200);
                }
            }
        }, 500);
        
        // Efectos en las respuestas incorrectas
        const respuestasIncorrectas = document.querySelectorAll('.incorrecta');
        respuestasIncorrectas.forEach((respuesta, index) => {
            setTimeout(() => {
                const rect = respuesta.getBoundingClientRect();
                const x = rect.left + rect.width / 2;
                const y = rect.top + rect.height / 2;
                
                crearExplosión(x, y, 5, 'muerte');
            }, 1000 + index * 300);
        });
    }
    
    // Efecto de partículas aleatorias en el fondo
    function crearPartículaAleatoria() {
        if (Math.random() < 0.1) { // 10% de probabilidad
            const x = Math.random() * window.innerWidth;
            const y = Math.random() * window.innerHeight;
            const tipos = ['magia', 'cura', 'fuego', 'hielo'];
            const tipo = tipos[Math.floor(Math.random() * tipos.length)];
            
            crearPartícula(x, y, tipo);
        }
    }
    
    // Crear partículas aleatorias periódicamente
    setInterval(crearPartículaAleatoria, 1000);
    
    // Efecto de parallax al mover el ratón
    document.addEventListener('mousemove', function(e) {
        if (efectoActivo) return;
        
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        // Mover sutilmente el fondo
        document.body.style.backgroundPosition = `${50 + x * 5}% ${50 + y * 5}%`;
        
        // Crear partículas ocasionales al mover el ratón
        if (Math.random() < 0.05) { // 5% de probabilidad
            crearPartícula(e.clientX, e.clientY, 'magia');
        }
    });
    
    // Efectos al hacer clic en cualquier parte
    document.addEventListener('click', function(e) {
        if (efectoActivo) return;
        
        // No crear efectos si se hace clic en un botón o enlace
        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || e.target.tagName === 'INPUT') {
            return;
        }
        
        crearExplosión(e.clientX, e.clientY, 5, 'magia');
    });
    
    // Efecto de sonido ambiente (simulado)
    function reproducirSonidoAmbiente() {
        // En un proyecto real, aquí se cargaría un archivo de sonido
        // const audio = new Audio('sonidos/ambiente.mp3');
        // audio.volume = 0.1;
        // audio.loop = true;
        // audio.play();
    }
    
    // Iniciar sonido ambiente al cargar la página
    // reproducirSonidoAmbiente();
    
    // Efecto de carga inicial
    window.addEventListener('load', function() {
        // Crear explosión inicial en el centro de la pantalla
        crearExplosión(window.innerWidth / 2, window.innerHeight / 2, 30, 'magia');
        
        // Crear texto flotante de bienvenida
        setTimeout(() => {
            crearTextoFlotante(window.innerWidth / 2, window.innerHeight / 2, '¡Bienvenido al mundo de Tibia!', '#ffcc33');
        }, 1000);
    });
});
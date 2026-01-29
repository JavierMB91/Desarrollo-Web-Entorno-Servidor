document.addEventListener('DOMContentLoaded', () => {
    // Evitar duplicados si el script se carga dos veces
    if (document.querySelector('.asistente-container')) return;

    // Variable para almacenar datos de la BD
    let libreriaData = { servicios: [], noticias: [] };

    // Cargar datos desde la API PHP
    fetch('asistente_api.php')
        .then(res => res.json())
        .then(data => {
            libreriaData = data;
        })
        .catch(err => console.error("Error cargando datos del asistente:", err));

    // 1. Inyectar HTML del Asistente y los Iconos de Google
    const asistenteHTML = `
        <button class="asistente-toggler">
            <span class="material-symbols-outlined">chat</span>
            <span class="material-symbols-outlined">close</span>
        </button>
        <div class="asistente">
            <header>
                <h2>Asistente</h2>
                <span class="close-btn material-symbols-outlined" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;">close</span>
            </header>
            <ul class="chatbox">
                <li class="chat incoming">
                    <span class="material-symbols-outlined" style="margin-right: 10px; align-self: flex-end; color: var(--dorado-viejo);">smart_toy</span>
                    <div>
                        <p>Hola 👋<br>Soy el asistente virtual. ¿En qué puedo ayudarte hoy?</p>
                        <div class="chat-options">
                            <button class="option-btn">Horarios</button>
                            <button class="option-btn">Servicios</button>
                            <button class="option-btn">Noticias</button>
                            <button class="option-btn">Contacto</button>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="chat-input">
                <textarea placeholder="Escribe tu consulta..." spellcheck="false" required></textarea>
                <span id="send-btn" class="material-symbols-outlined">send</span>
            </div>
        </div>
    `;
    
    // Cargar iconos Material Symbols si no existen
    if (!document.querySelector('link[href*="material-symbols-outlined"]')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0';
        document.head.appendChild(link);
    }

    const divContainer = document.createElement('div');
    divContainer.classList.add('asistente-container');
    divContainer.innerHTML = asistenteHTML;
    document.body.appendChild(divContainer);

    // 2. Lógica del Asistente
    const asistenteToggler = document.querySelector(".asistente-toggler");
    const closeBtn = document.querySelector(".close-btn");
    const chatbox = document.querySelector(".chatbox");
    const chatInput = document.querySelector(".chat-input textarea");
    const sendChatBtn = document.querySelector(".chat-input span");

    let userMessage = null;
    const inputInitHeight = chatInput.scrollHeight;

    // Función para crear elementos de mensaje
    const createChatLi = (message, className) => {
        const chatLi = document.createElement("li");
        chatLi.classList.add("chat", className);
        
        let chatContent = className === "outgoing" 
            ? `<p></p>` 
            : `<span class="material-symbols-outlined" style="margin-right: 10px; align-self: flex-end; color: var(--dorado-viejo);">smart_toy</span><p></p>`;
        
        chatLi.innerHTML = chatContent;
        chatLi.querySelector("p").textContent = message;
        return chatLi;
    }

    // Generar respuesta basada en palabras clave
    const generateResponse = (incomingChatLi) => {
        const messageElement = incomingChatLi.querySelector("p");
        const msg = userMessage.toLowerCase();
        
        let response = "Lo siento, no entiendo tu consulta. Por favor contacta con administración al 600 123 456.";

        // Lógica de respuestas
        if (msg.includes("hola") || msg.includes("buenos")) {
            response = "¡Hola! Bienvenido a la Libreria. Pregúntame sobre horarios, servicios o cómo hacerte socio.";
        } else if (msg.includes("horario") || msg.includes("hora") || msg.includes("abierto")) {
            response = "Nuestro horario es de Lunes a Domingo de 08:00 a 22:00 ininterrumpidamente.";
        } else if (msg.includes("precio") || msg.includes("costo") || msg.includes("tarifa") || msg.includes("cuota")) {
            response = "Algunos servicios son gratuitos (préstamo de libros) y otros tienen coste (sala estudio 6€, asesoramiento 15€). Consulta la sección 'Actividades'.";
            // Usar datos reales de la BD
            if (libreriaData.servicios.length > 0) {
                let precios = libreriaData.servicios.slice(0, 3).map(s => `• ${s.nombre}: ${s.precio}`).join("\n");
                response = "Aquí tienes algunas de nuestras tarifas:\n" + precios + "\n\nEntra en nuestra sección 'Actividades' para ver más.";
            }
        } else if (msg.includes("donde") || msg.includes("ubicacion") || msg.includes("direccion") || msg.includes("calle")) {
            response = "Estamos ubicados en la calle Principal 123, en el centro de la ciudad.";
        } else if (msg.includes("actividad") || msg.includes("servicio") || msg.includes("libro")) {
            response = "Ofrecemos préstamo de libros, salas de estudio, club de lectura y acceso a ordenadores. ¡Mira la sección Actividades!";
            // Usar datos reales de la BD
            if (libreriaData.servicios.length > 0) {
                let lista = libreriaData.servicios.slice(0, 3).map(s => `• ${s.nombre}`).join("\n");
                response = "Estos son algunos de nuestros servicios:\n" + lista + "\n\nEntra en nuestra sección 'Actividades' para ver más.";
            }
        } else if (msg.includes("noticia") || msg.includes("novedad") || msg.includes("evento")) {
            if (libreriaData.noticias.length > 0) {
                let listaNoticias = libreriaData.noticias.map(n => `📰 ${n.titulo}`).join("\n"); // La API ya limita a 3
                response = "Estas son las últimas novedades:\n" + listaNoticias + "\n\nEntra en nuestra sección 'Noticias' para ver más.";
            } else {
                response = "No hay noticias recientes por el momento.";
            }
        } else if (msg.includes("registro") || msg.includes("socio") || msg.includes("apuntar")) {
            response = "Puedes registrarte como socio haciendo clic en el botón 'Registrarse' del menú superior.";
        } else if (msg.includes("cita") || msg.includes("reserva")) {
            response = "Para reservar una sala o servicio, debes iniciar sesión y acceder al apartado de Reservas.";
        } else if (msg.includes("contacto") || msg.includes("telefono")) {
            response = "Puedes contactarnos en el formulario de la web o llamando al 600 123 456.";
        }

        // Simular pequeño retraso de pensamiento
        setTimeout(() => {
            messageElement.textContent = response;
            chatbox.scrollTo(0, chatbox.scrollHeight);
        }, 600);
    }

    const handleChat = (message = null) => {
        userMessage = message || chatInput.value.trim();
        if(!userMessage) return;

        chatInput.value = "";
        chatInput.style.height = `${inputInitHeight}px`;

        // Añadir mensaje del usuario
        chatbox.appendChild(createChatLi(userMessage, "outgoing"));
        chatbox.scrollTo(0, chatbox.scrollHeight);

        // Añadir mensaje de "Escribiendo..."
        setTimeout(() => {
            const incomingChatLi = createChatLi("Escribiendo...", "incoming");
            chatbox.appendChild(incomingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight);
            generateResponse(incomingChatLi);
        }, 600);
    }

    // Manejar clics en los botones de opciones
    chatbox.addEventListener('click', (e) => {
        if (e.target.classList.contains('option-btn')) {
            const optionText = e.target.textContent;
            handleChat(optionText);
        }
    });

    chatInput.addEventListener("input", () => {
        chatInput.style.height = `${inputInitHeight}px`;
        chatInput.style.height = `${chatInput.scrollHeight}px`;
    });

    chatInput.addEventListener("keydown", (e) => {
        if(e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
            e.preventDefault();
            handleChat();
        }
    });

    sendChatBtn.addEventListener("click", () => handleChat());
    asistenteToggler.addEventListener("click", () => document.body.classList.toggle("show-asistente"));
});
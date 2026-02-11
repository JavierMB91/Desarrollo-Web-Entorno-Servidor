# Librería - Aplicación Web de Gestión

Bienvenido al repositorio del proyecto **Librería**. Esta es una aplicación web completa desarrollada en PHP y MySQL para la gestión integral de una librería, permitiendo la administración de socios, servicios, reservas y noticias.

## 📋 Características Principales

### 1. Gestión de Usuarios (Socios/Lectores)
- **CRUD Completo:** Registro, listado, edición y eliminación de socios.
- **Perfiles:** Subida y actualización de fotos de perfil (validación de formato JPG/JPEG y tamano).
- **Seguridad:** Control de acceso basado en roles (Administrador vs. Usuario) y claves hasheadas.
- **Validación:** Comprobaciones robustas tanto en cliente (JavaScript) como en servidor (PHP) para campos como DNI, teléfono y edad.

### 2. Gestión de Servicios
- Administración de las actividades del club.
- Control de detalles: nombre, descripción, duración (para eventos/talleres), precio y horarios.
- Validación de datos específicos (ej. duración mínima de 15 min).

### 3. Sistema de Reservas y Calendario
- Reserva de salas de estudio o inscripción a eventos por parte de los socios.
- Validación de fechas (no permitir fechas pasadas) y horarios.
- Visualización de disponibilidad.

### 4. Interfaz y Diseno (UI/UX)
- **Diseno Responsivo:** Adaptado a móviles, tablets y escritorio mediante Media Queries.
- **Estética Personalizada:** Tema visual "Dark Academia" / Elegante utilizando variables CSS (`--ebano-oscuro`, `--dorado-viejo`, `--papel-antiguo`).
- **Componentes Interactivos:** Menú hamburguesa, desplegables de usuario, modales y un asistente virtual (chatbot UI) para resolver dudas sobre la librería.

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP (Uso de PDO para conexiones seguras a base de datos).
- **Base de Datos:** MySQL.
- **Frontend:** HTML5, CSS3 (Flexbox, Grid, Animaciones), JavaScript (Vanilla).
- **Entorno:** Compatible con XAMPP (Local) e InfinityFree (Producción).

## 📂 Estructura del Proyecto

La estructura principal dentro de la carpeta `app/` es la siguiente:

```text
app/
├── css/
│   └── estilos.css          # Hoja de estilos principal con diseno responsivo
├── js/
│   ├── funcionesAnadirSocio.js  # Validaciones para nuevos socios
│   ├── funcionesEditarSocio.js  # Validaciones para edición y fotos
│   ├── funcionesServicio.js     # Lógica para gestión de servicios
│   ├── funcionesCita.js         # Lógica para el sistema de reservas
│   └── ...
├── uploads/
│   └── usuarios/            # Directorio para imágenes de perfil de socios
├── conexion.php             # Configuración de conexión a BD (Local/Prod)
├── editarSocio.php          # Controlador y vista para editar socios
├── editarServicio.php       # Controlador y vista para editar servicios
└── ...
```

## 🚀 Instalación y Configuración

1. **Base de Datos:**
   - Asegúrate de tener un servidor MySQL corriendo.
   - Importa el esquema de la base de datos (tablas: `usuario`, `servicio`, `cita`, etc.).
 
2. **Configuración de Conexión (`conexion.php`):**
   El sistema detecta automáticamente el entorno.
   - **Localhost:** Utiliza variables de entorno (`DB_HOST`, `DB_USER`...) o configura tus credenciales locales en el archivo.
   - **Producción:** Configurado predeterminadamente para el entorno InfinityFree.

3. **Permisos:**
   Asegúrate de que la carpeta `app/uploads/usuarios` tenga permisos de escritura para permitir la subida de imágenes.

## 🔍 Detalles de Implementación

- **Validación de Formularios:** Se utilizan Expresiones Regulares (Regex) en JavaScript para validar nombres, teléfonos espanoles (9 dígitos), precios y formatos de archivo antes de enviar al servidor.
- **Manejo de Errores:** Sistema de feedback visual mediante clases CSS (`.error`, `.mensaje-exito`) y manejo de excepciones `PDOException` en la base de datos.
- **Estilos:** Uso extensivo de variables CSS (`:root`) para mantener la consistencia en la paleta de colores y transiciones suaves.

---
 
### Autor
Desarrollado como parte del módulo de **Desarrollo Web en Entorno Servidor (2º DAW)**.

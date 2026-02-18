# 📚 Módulo de Catálogo de Libros

Este documento detalla la implementación del buscador de libros, una de las funcionalidades más interactivas de la aplicación. Su propósito es ofrecer a los usuarios una herramienta potente y rápida para explorar un vasto catálogo de libros, cómics y revistas.

## ✨ Características Principales

- **Búsqueda Avanzada y Filtrada:** Permite a los usuarios acotar sus búsquedas por **Categoría** (ej. `Fantasía`), **Título** (ej. `El Quijote`) o **Autor** (ej. `Stephen King`), ofreciendo resultados mucho más precisos.
- **Interfaz Reactiva y sin Recargas:** La página se comporta como una *Single-Page Application (SPA)*. Las búsquedas y la navegación entre páginas de resultados se realizan de forma asíncrona, actualizando el contenido dinámicamente sin necesidad de recargar la página.
- **Sistema Híbrido de Portadas:** Combina dos APIs para maximizar la disponibilidad de imágenes de portada. Utiliza la **Google Books API** como fuente principal y, si esta falla, recurre a la **Open Library Covers API** como un ingenioso "Plan B".
- **Paginación Rápida en el Cliente:** Carga un lote de 40 resultados y los organiza en 4 páginas de 10. Esto permite una navegación entre páginas casi instantánea, ya que no requiere nuevas peticiones a la red.
- **Información Enriquecida:** Cada libro se presenta en una "tarjeta" que incluye no solo la portada, título y autor, sino también datos adicionales como la **valoración media** (representada con estrellas) y el **año de publicación**.
- **Diseño Integrado:** Los estilos de las tarjetas y el formulario están unificados con el resto de la aplicación en `css/estilos.css`.

---

## 🛠️ APIs Utilizadas

1.  **Google Books API:**
    - **Rol:** Fuente primaria de datos. Es la más completa en cuanto a la cantidad de libros indexados y metadatos como valoraciones, sinopsis, etc.
    - **Endpoint:** `https://www.googleapis.com/books/v1/volumes`
    - **Parámetros Clave Usados:** `q` (la consulta), `maxResults` (para limitar a 40) y `key` (la clave de API).

2.  **Open Library Covers API:**
    - **Rol:** Sistema de respaldo para las imágenes de portada. Es extremadamente eficiente para este propósito.
    - **Endpoint:** `https://covers.openlibrary.org/b/isbn/{ISBN}-M.jpg`
    - **Funcionamiento:** Se le pasa un ISBN y devuelve directamente la imagen de la portada en el tamaño mediano (`-M`).

---

## ⚙️ Implementación Técnica

- **Frontend (JavaScript):**
    - **Motor de Búsqueda:** Utiliza la API `fetch` para comunicarse de forma asíncrona con las APIs externas.
    - **Manipulación del DOM:** "Pinta" dinámicamente las tarjetas de los libros, los mensajes de estado (`Buscando...`, `No se encontraron resultados...`) y los controles de paginación.
    - **Gestión de Eventos:** Captura el envío del formulario (`submit`) y los clics en los botones de paginación para ejecutar las acciones correspondientes sin recargar la página.

- **Backend (PHP):**
    - **Servidor de HTML:** Su única tarea es servir la estructura HTML inicial de la página, incluyendo los `includes` de la cabecera, el pie de página y la navegación.
    - **Cargador de Scripts:** Enlaza el archivo `js/buscar_libros.js` para que el navegador pueda descargarlo y ejecutarlo.

---

## 🧠 Lógica Clave

### Estrategia de Imágenes (Fallback Inteligente)

Para combatir el problema de las portadas faltantes, se sigue un proceso robusto:

1.  **Intento 1 (Google Books):** Se busca una URL de imagen en el objeto de respuesta de Google (`volumeInfo.imageLinks`). Si existe, se utiliza. Se fuerza el protocolo a `https` para evitar problemas de contenido mixto.
2.  **Intento 2 (Open Library):** Si el paso 1 falla, el código busca un identificador `ISBN_13` o `ISBN_10` en los datos del libro.
3.  **Construcción de URL:** Si se encuentra un ISBN, se construye la URL para la API de Open Library.
4.  **Manejo de Errores Final:** La etiqueta `<img>` en el HTML incluye un manejador de eventos `onerror`. Si la URL (ya sea de Google o de Open Library) falla al cargar, este evento se dispara y cambia la fuente de la imagen a un *placeholder* genérico. Esto evita que el usuario vea iconos de imágenes rotas.

```html
<img src="..." onerror="this.onerror=null;this.src='URL_DEL_PLACEHOLDER';">
```

### Paginación en el Cliente

Esta técnica se eligió para priorizar la velocidad de la experiencia de usuario al navegar entre resultados.

- **Ventaja:** La navegación entre las páginas 2, 3 y 4 es instantánea porque los datos ya están en la memoria del navegador.
- **Desventaja:** La funcionalidad está limitada a los primeros 40 resultados que devuelve la API. No se puede acceder al resultado 41 y posteriores.
- **Implementación:** La función `renderPage(page)` utiliza el método `array.slice()` para "cortar" el trozo del array `allBooks` que corresponde a la página solicitada.

---

## 📂 Archivos Involucrados

Los archivos clave para esta funcionalidad son:

```
app/
├── 📄 buscar_libros.php   # Estructura HTML y carga del script.
│
├── 📁 js/
│   └── 📜 buscar_libros.js  # Contiene TODA la lógica de la API y del DOM.
│
└── 📁 css/
    └── 🎨 estilos.css       # Contiene los estilos para las tarjetas, formulario, etc.
```

## 🔮 Posibles Mejoras Futuras

- **Paginación del Lado del Servidor:** Modificar la lógica para usar el parámetro `startIndex` de la API de Google. Esto permitiría navegar por *todos* los resultados disponibles, aunque cada cambio de página sería ligeramente más lento al requerir una nueva petición a la red.
- **Vista de Detalle:** Implementar una ventana modal o una nueva página que se muestre al hacer clic en una tarjeta, mostrando información más detallada como la sinopsis, número de páginas, editorial, etc.
- **Lista de Favoritos:** Añadir un botón en cada tarjeta para que los usuarios logueados puedan guardar libros en una lista personal, lo que requeriría una nueva tabla en la base de datos y lógica de backend.
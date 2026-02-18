# 📚 Funcionalidad de Catálogo de Libros

Esta sección de la aplicación implementa un buscador de libros dinámico que se comunica con APIs externas para ofrecer un catálogo extenso y detallado. La interfaz es interactiva y está construida con JavaScript moderno, separando la lógica del frontend de la presentación del backend.

## ✨ Características Principales

- **Búsqueda Avanzada:** Permite a los usuarios buscar libros por **Categoría**, **Título** o **Autor**.
- **Integración de APIs Híbrida:** Utiliza la **Google Books API** como fuente principal de datos y la **Open Library Covers API** como sistema de respaldo para las portadas.
- **Paginación Eficiente en el Cliente:** Carga hasta 40 resultados de una vez y los presenta en páginas de 10, permitiendo una navegación rápida sin recargar la página ni hacer nuevas llamadas a la API.
- **Interfaz Dinámica (SPA-like):** Construida con JavaScript (Vanilla JS) y la API `fetch` para realizar peticiones asíncronas y actualizar el DOM sin recargas.
- **Información Enriquecida:** Muestra detalles adicionales como la valoración media (con estrellas) y el año de publicación para cada libro.
- **Diseño Integrado:** Los estilos de las tarjetas y el formulario están unificados con el resto de la aplicación en `css/estilos.css`.

## 🛠️ APIs Utilizadas

1.  **Google Books API:**
    - **Rol:** Fuente principal de datos.
    - **Información obtenida:** Título, autor, sinopsis, ISBN, valoración media, fecha de publicación, y URL de la imagen de portada (si está disponible).

2.  **Open Library Covers API:**
    - **Rol:** Sistema de respaldo o "Plan B" para las imágenes.
    - **Funcionamiento:** Si Google no proporciona una imagen de portada, el sistema utiliza el ISBN del libro para solicitar la portada a Open Library, aumentando significativamente la cobertura de imágenes.

## ⚙️ Implementación Técnica

- **Frontend (JavaScript):**
    - Toda la lógica reside en un bloque `<script>` dentro de `buscar_libros.php`.
    - Usa `fetch` para las llamadas a las APIs.
    - Manipula el DOM para "pintar" las tarjetas de los libros, los mensajes de estado y los controles de paginación.
    - El evento `submit` del formulario se intercepta con `event.preventDefault()` para evitar la recarga de la página.

- **Backend (PHP):**
    - El rol de `buscar_libros.php` es mínimo: sirve la estructura HTML inicial y los `includes` de la cabecera y navegación. No procesa datos de la API.

## 🧠 Lógica Clave

### Construcción de la Búsqueda
La consulta a la API se construye dinámicamente uniendo el filtro seleccionado con el término de búsqueda. Por ejemplo: `subject:Fantasía` o `inauthor:Stephen King`.

### Estrategia de Imágenes (Fallback)

1.  Se intenta obtener la URL de la imagen desde `volumeInfo.imageLinks` de la respuesta de Google.
2.  Si falla, se busca un identificador `ISBN_13` o `ISBN_10` en `volumeInfo.industryIdentifiers`.
3.  Si se encuentra un ISBN, se construye la URL de Open Library: `https://covers.openlibrary.org/b/isbn/{ISBN}-M.jpg`.
4.  Se utiliza el atributo `onerror` en la etiqueta `<img>` para mostrar una imagen genérica si la URL de Open Library también falla, evitando iconos de imagen rota.

### Paginación en el Cliente

1.  Se solicitan 40 resultados a la API (`maxResults=40`).
2.  Estos 40 resultados se guardan en un array de JavaScript (`allBooks`).
3.  La función `renderPage(page)` utiliza `array.slice()` para extraer y mostrar solo los 10 libros que corresponden a la página actual.

## 📂 Archivos Involucrados

- `app/buscar_libros.php`: Contiene el HTML y toda la lógica JavaScript de la funcionalidad.
- `app/css/estilos.css`: Contiene los estilos para `.libro-card`, `.form-buscador-libros`, etc.
- `app/nav.php`: Donde se añade el enlace "Catálogo" para acceder a la página.
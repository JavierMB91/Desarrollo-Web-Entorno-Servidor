<?php
session_start();
require_once 'conexion.php'; // Reutilizamos tu conexión
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <?php $tituloPagina = "Buscador de Libros"; ?>
    <?php include 'head.php'; ?>
</head>
<body>
    <div class="container">
        <header>
            <div class="titulo-con-logo">
                <h1 class="titulo-principal">Catálogo de Libros</h1>
            </div>
            <?php include 'nav.php'; ?>
        </header>

        <main>
            <!-- BUSCADOR -->
            <form id="search-form" class="form-buscador-libros">
                <div>
                    <label for="filter-type">Buscar por:</label>
                    <select id="filter-type" name="filter_type">
                        <option value="subject" selected>Categoría</option>
                        <option value="intitle">Título</option>
                        <option value="inauthor">Autor</option>
                    </select>
                </div>
                <div>
                    <label for="search-input">Término de búsqueda:</label>
                    <input type="text" id="search-input" name="q" placeholder="Fantasía, El Quijote, Stephen King..." value="">
                </div>
                <div class="contenedor-botones">
                    <button type="submit"><span>Buscar</span></button>
                </div>
            </form>

            <!-- CONTENEDOR PARA MENSAJES (Cargando, No encontrado, etc.) -->
            <div id="search-status" class="resultados-busqueda"></div>

            <!-- CONTENEDOR DONDE SE PINTARÁN LOS LIBROS -->
            <div class="libros-grid" id="libros-grid">
                <!-- Los resultados de los libros se insertarán aquí con JavaScript -->
            </div>

            <!-- CONTENEDOR PARA LA PAGINACIÓN -->
            <div id="pagination-controls" class="paginacion">
                <!-- Los botones de paginación se insertarán aquí -->
            </div>
        </main>

        <div id="footer"></div>
        <script src="js/footer.js"></script>
        
        <script>
            // Esperamos a que todo el HTML de la página esté cargado
            document.addEventListener('DOMContentLoaded', () => {

                // --- A. OBTENER REFERENCIAS A LOS ELEMENTOS DEL HTML ---
                const searchForm = document.getElementById('search-form');
                const searchInput = document.getElementById('search-input');
                const librosGrid = document.getElementById('libros-grid');
                const searchStatus = document.getElementById('search-status');
                const filterType = document.getElementById('filter-type'); // <-- Nuevo: Obtenemos el selector
                const paginationControls = document.getElementById('pagination-controls');
                
                const apiKey = 'AIzaSyCCv_eHGyQgjs_DjRostk8xhWHdnYMCu40';
                const resultsPerPage = 10;
                let allBooks = [];
                let currentPage = 1;

                // --- FUNCIÓN AUXILIAR PARA GENERAR ESTRELLAS ---
                const generarEstrellas = (rating) => {
                    const ratingValue = rating || 0;
                    const estrellasLlenas = Math.round(ratingValue);
                    let estrellasHTML = '';
                    for (let i = 1; i <= 5; i++) {
                        // Si el índice es menor o igual a las estrellas llenas, la pinta. Si no, la deja vacía.
                        estrellasHTML += `<span class="star ${i <= estrellasLlenas ? 'filled' : ''}">★</span>`;
                    }
                    return `<div class="libro-rating">${estrellasHTML}</div>`;
                };


                // --- B. FUNCIÓN PARA RENDERIZAR UNA PÁGINA DE LIBROS ---
                const renderPage = (page) => {
                    currentPage = page;
                    librosGrid.innerHTML = ''; // Limpiar la grilla

                    const start = (page - 1) * resultsPerPage;
                    const end = start + resultsPerPage;
                    const booksToShow = allBooks.slice(start, end);

                    booksToShow.forEach(book => {
                        const volumeInfo = book.volumeInfo;
                        const title = volumeInfo.title || 'Título no disponible';
                        const authors = volumeInfo.authors ? volumeInfo.authors.join(', ') : 'Autor desconocido';
                        
                        // LÓGICA MEJORADA PARA IMÁGENES (Google + Fallback a Open Library) - MÁS ROBUSTA
                        let thumbnail = 'https://via.placeholder.com/150x220.png?text=No+Image';
                        
                        // Prioridad 1: Intentar con Google Books de forma segura
                        const googleThumbnail = volumeInfo.imageLinks?.thumbnail || volumeInfo.imageLinks?.smallThumbnail;
                        if (googleThumbnail) {
                            thumbnail = googleThumbnail.replace(/^http:\/\//i, 'https://');
                        } 
                        // Prioridad 2: Si no, intentar con Open Library usando ISBN
                        else if (volumeInfo.industryIdentifiers) {
                            const isbn = volumeInfo.industryIdentifiers.find(id => id.type === 'ISBN_13' || id.type === 'ISBN_10');
                            if (isbn) {
                                thumbnail = `https://covers.openlibrary.org/b/isbn/${isbn.identifier}-M.jpg`;
                            }
                        }


                        const publishedDate = volumeInfo.publishedDate ? new Date(volumeInfo.publishedDate).getFullYear() : 'N/A';
                        const averageRating = volumeInfo.averageRating;

                        const bookCardHTML = `
                            <div class="libro-card">
                                <img src="${thumbnail}" alt="Portada de ${title}" loading="lazy" onerror="this.onerror=null;this.src='https://via.placeholder.com/150x220.png?text=No+Image';">
                                <h3>${title}</h3>
                                <p>${authors}</p>
                                <div class="libro-info-extra">
                                    ${generarEstrellas(averageRating)}
                                    <span class="libro-fecha">Año: ${publishedDate}</span>
                                </div>
                            </div>
                        `;
                        librosGrid.innerHTML += bookCardHTML;
                    });
                };

                // --- C. FUNCIÓN PARA RENDERIZAR LA PAGINACIÓN ---
                const renderPagination = () => {
                    paginationControls.innerHTML = ''; // Limpiar controles
                    const totalPages = Math.ceil(allBooks.length / resultsPerPage);

                    if (totalPages <= 1) return; // No mostrar paginación si solo hay una página

                    // Botón Anterior
                    if (currentPage > 1) {
                        const prevButton = document.createElement('button');
                        prevButton.textContent = 'Anterior';
                        prevButton.classList.add('btn');
                        prevButton.addEventListener('click', () => {
                            renderPage(currentPage - 1);
                            renderPagination(); // Re-render para actualizar estado
                        });
                        paginationControls.appendChild(prevButton);
                    }

                    // Indicador de página
                    const pageIndicator = document.createElement('span');
                    pageIndicator.textContent = `Página ${currentPage} de ${totalPages}`;
                    paginationControls.appendChild(pageIndicator);

                    // Botón Siguiente
                    if (currentPage < totalPages) {
                        const nextButton = document.createElement('button');
                        nextButton.textContent = 'Siguiente';
                        nextButton.classList.add('btn');
                        nextButton.addEventListener('click', () => {
                            renderPage(currentPage + 1);
                            renderPagination(); // Re-render para actualizar estado
                        });
                        paginationControls.appendChild(nextButton);
                    }
                };

                // --- D. FUNCIÓN PRINCIPAL DE BÚSQUEDA ---
                const buscarLibros = (query, filter) => {
                    librosGrid.innerHTML = '';
                    paginationControls.innerHTML = ''; // Limpiar paginación en nueva búsqueda
                    
                    const apiQuery = `${filter}:${query}`;
                    const nombreFiltro = filterType.options[filterType.selectedIndex].text;

                    searchStatus.innerHTML = `<p>Buscando por ${nombreFiltro.toLowerCase()}: "${query}"...</p>`;

                    // Pedimos los 40 resultados más relevantes (quitamos el orden por "newest")
                    const apiUrl = `https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(apiQuery)}&maxResults=40&key=${apiKey}`;

                    fetch(apiUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('La respuesta de la red no fue correcta.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            searchStatus.innerHTML = ''; // Limpiamos el mensaje de "cargando"

                            if (data.items && data.items.length > 0) {
                                allBooks = data.items; // Guardamos todos los libros
                                renderPage(1);         // Renderizamos la primera página
                                renderPagination();    // Renderizamos los controles de paginación
                            } else {
                                allBooks = [];
                                searchStatus.innerHTML = `<p>No se encontraron libros para la búsqueda: '${query}'.</p>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error al buscar libros:', error);
                            allBooks = [];
                            searchStatus.innerHTML = '<p class="error">❌ Hubo un problema al conectar con la API de Google Books.</p>';
                        });
                };

                // --- E. ESCUCHAR EL EVENTO DE ENVÍO DEL FORMULARIO ---
                searchForm.addEventListener('submit', (event) => {
                    event.preventDefault(); // ¡Muy importante! Evita que la página se recargue.
                    const query = searchInput.value.trim();
                    const filter = filterType.value; // <-- Nuevo: Obtenemos el valor del filtro
                    if (query) {
                        buscarLibros(query, filter);
                    }
                });

                // No se realiza búsqueda inicial para que la página cargue limpia.
            });
        </script>

    </div>
</body>
</html>
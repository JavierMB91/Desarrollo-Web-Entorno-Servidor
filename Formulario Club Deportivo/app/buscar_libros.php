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
        <!-- Cargamos el script externo para la lógica del buscador -->
        <script src="js/buscar_libros.js?v=<?php echo filemtime('js/buscar_libros.js'); ?>"></script>

    </div>
</body>
</html>
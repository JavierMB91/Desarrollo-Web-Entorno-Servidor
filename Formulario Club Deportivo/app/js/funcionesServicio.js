const formularioServicio = document.getElementById('formulario-servicio');

// ==========================
// CONTADOR DEL TEXTAREA
// ==========================
const textarea = document.getElementById('descripcion');
const contador = document.getElementById('contador');

formularioServicio.addEventListener('submit', (e) => {
    e.preventDefault();

    let hayError = false; // <-- CONTROL DE ERRORES

    // Limpiar errores previos
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    // Valores del formulario
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const duracion = document.getElementById('duracion').value;
    const precio = document.getElementById('precio').value;

    // Expresiones regulares
    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    const soloMinutos = /^[0-9]+$/;
    const soloNumeros = /^[0-9]+(\.[0-9]{1,2})?$/;

    // ============================
    // VALIDACIÓN NOMBRE
    // ============================
    if (nombre.trim().length < 3 || nombre.trim().length > 50 || !soloLetras.test(nombre.trim())) {
        document.getElementById('nombreError').innerText = "Nombre no válido";
        hayError = true;
    }

    // ============================
    // VALIDACIÓN DESCRIPCIÓN
    // ============================
    if (descripcion.trim().length < 3) {
        document.getElementById('descripcionError').innerText = "Escribe un contenido válido.";
        hayError = true;
    }

    // ============================
    // VALIDACIÓN DURACIÓN
    // ============================
    if (!soloMinutos.test(duracion.trim()) || Number(duracion.trim()) < 15) {
        document.getElementById('duracionError').innerText = "Duración mínima 15 min";
        hayError = true;
    }

    // ============================
    // VALIDACIÓN PRECIO
    // ============================
    if (!soloNumeros.test(precio.trim()) || Number(precio.trim()) < 0) {
        document.getElementById('precioError').innerText = "Introduce un precio válido";
        hayError = true;
    }

    // Nota: validación de hora eliminada (hora gestionada en el servidor o formulario).

    // ============================
    // ENVIAR FORMULARIO
    // ============================
    if (!hayError) {
        formularioServicio.submit();
    }
});

// ============================
// CONTADOR DE CARACTERES
// ============================
textarea.addEventListener('input', function () {
    contador.textContent = this.value.length;
});

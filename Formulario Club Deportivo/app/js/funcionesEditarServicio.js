const formularioServicio = document.getElementById('formulario-servicio');

formularioServicio.addEventListener('submit', (e) => {
    e.preventDefault();

    let errores = false;

    // LIMPIAR ERRORES
    document.querySelectorAll('.error').forEach(span => span.innerText = "");

    // CAPTURA DE DATOS
    const nombre = document.getElementById('nombre').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const duracion = document.getElementById('duracion').value.trim();
    const precio = document.getElementById('precio').value.trim();
    const hora = document.getElementById('hora').value.trim();

    // EXPRESIONES REGULARES
    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    const soloNumeros = /^[0-9]+$/;
    const soloPrecio = /^[0-9]+(\.[0-9]{1,2})?$/;
    const formatoHora = /^([01]\d|2[0-3]):([0-5]\d)$/;

    // =============================
    // VALIDAR NOMBRE
    // =============================
    if (nombre.length < 3 || nombre.length > 50 || !soloLetras.test(nombre)) {
        document.getElementById('nombreError').innerText = "Introduce un nombre válido (solo letras).";
        errores = true;
    }

    // =============================
    // VALIDAR DESCRIPCIÓN
    // =============================
    if (descripcion.length < 3 || descripcion.length > 300) {
        document.getElementById('descripcionError').innerText = "La descripción debe tener entre 3 y 300 caracteres.";
        errores = true;
    }

    // =============================
    // VALIDAR DURACIÓN
    // =============================
    if (!soloNumeros.test(duracion) || Number(duracion) < 15) {
        document.getElementById('duracionError').innerText = "Duración mínima 15 minutos.";
        errores = true;
    }

    // =============================
    // VALIDAR PRECIO (permitir 0 = Gratuito)
    // =============================
    if (!soloPrecio.test(precio) || Number(precio) < 0) {
        document.getElementById('precioError').innerText = "Introduce un precio válido.";
        errores = true;
    }

    // =============================
    // VALIDAR HORA FORMATO ESPAÑA
    // =============================
    if (!formatoHora.test(hora)) {
        document.getElementById('horaError').innerText = "Hora inválida (ej: 09:30, 18:45).";
        errores = true;
    }

    // =============================
    // ENVIAR
    // =============================
    if (!errores) {
        formularioServicio.submit();
    }
});

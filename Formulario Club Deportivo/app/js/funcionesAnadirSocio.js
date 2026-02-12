const formularioNuevoSocio = document.getElementById('formularioNuevoSocio');

formularioNuevoSocio.addEventListener('submit', (e) => {
    e.preventDefault();

    let hayError = false; // <-- CONTROL REAL DE ERRORES

    // Limpiar errores anteriores
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    // Valores del formulario
    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const edad = document.querySelector('input[name="edad"]').value.trim();
    const telefono = document.querySelector('input[name="telefono"]').value.trim();
    const password = document.getElementById('password').value.trim();
    const foto = document.querySelector('input[name="foto"]');

    // Expresiones regulares
    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    const telefonoEspana = /^\d{9}$/;
    const soloLetrasNumerosGuiones = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9_]+$/;

    // =========================
    // VALIDACIONES
    // =========================

    // Validación nombre
    if (nombre.length < 4 || nombre.length > 50 || !soloLetras.test(nombre)) {
        mostrarError('nombre', 'Nombre no válido');
        hayError = true;
    }

    // Validación edad
    if (!edad || Number(edad) < 18) {
        mostrarError('edad', 'Debes ser mayor de edad');
        hayError = true;
    }

    // Validación teléfono
    if (!telefonoEspana.test(telefono)) {
        mostrarError('telefono', 'Teléfono no válido (9 dígitos)');
        hayError = true;
    }

    if (password.length < 8 || password.length > 20 || !soloLetrasNumerosGuiones.test(password)) {
        document.getElementById('passwordError').innerText = "Clave no válida (8-20 caracteres, solo letras, números y guion bajo)";
        hayError = true;
    }

    // Validación foto
    let fotoErrores = comprobarImagen(foto);
    if (fotoErrores.length > 0) {
        fotoErrores.forEach(error => mostrarError('foto', error));
        hayError = true;
    }

    // =========================
    // ENVIAR FORMULARIO SI TODO ESTÁ CORRECTO
    // =========================
    if (!hayError) {
        formularioNuevoSocio.submit();
    }
});


// ===============================
// FUNCIÓN PARA MOSTRAR ERRORES
// ===============================
function mostrarError(campo, mensaje) {
    let input = document.querySelector(`input[name="${campo}"]`);
    let span = input.nextElementSibling;

    if (!span || !span.classList.contains('error')) {
        span = document.createElement('span');
        span.classList.add('error');
        input.parentNode.appendChild(span);
    }

    span.innerText = mensaje;
}


// ===============================
// VALIDACIÓN DE IMAGEN
// ===============================
function comprobarImagen(imagen) {
    let errores = [];

    if (imagen.files.length === 0) {
        errores.push("No hay seleccionado ninguna imagen");
        return errores;
    }

    const archivo = imagen.files[0];

    // Validar extensión
    const nombreArchivo = archivo.name.toLowerCase();
    const extensionesPermitidas = ['.jpg', '.jpeg', '.png', '.webp'];
    const tieneExtensionValida = extensionesPermitidas.some(ext => nombreArchivo.endsWith(ext));
    
    if (!tieneExtensionValida) {
        errores.push("La imagen debe ser JPG, PNG o WEBP");
    }

    // Validar tipo MIME
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!tiposPermitidos.includes(archivo.type)) {
        errores.push("Formato de imagen no válido");
    }

    // Tamano máximo 5MB
    if (archivo.size > 5 * 1024 * 1024) {
        errores.push("La imagen es demasiado grande (máx 5MB)");
    }

    return errores;
}

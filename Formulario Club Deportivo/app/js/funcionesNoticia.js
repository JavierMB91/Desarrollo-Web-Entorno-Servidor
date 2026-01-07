const formulariocontenido = document.getElementById('formularioNoticia');

const textarea = document.getElementById('contenido');
const contador = document.getElementById('contador');

formulariocontenido.addEventListener('submit', (e) => {
    e.preventDefault();

    let hayError = false; // <-- CONTROL REAL DE ERRORES

    // Limpiar errores previos
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    // Valores
    const titulo = document.getElementById('titulo').value;
    const contenido = document.getElementById('contenido').value;
    const fecha = document.getElementById('fecha').value;
    const fechaError = document.getElementById('fechaError');
    const imagen = document.getElementById('imagen');

    // =========================
    // VALIDACIÓN TÍTULO
    // =========================
    if (titulo.trim().length < 3) {
        document.getElementById('tituloError').innerText = "Título demasiado corto.";
        hayError = true;
    }

    // =========================
    // VALIDACIÓN CONTENIDO
    // =========================
    if (contenido.trim().length < 3) {
        document.getElementById('noticiaError').innerText = "Escribe un contenido válido.";
        hayError = true;
    }

    // =========================
    // VALIDACIÓN FECHA
    // =========================
    if (!fecha) {
    fechaError.innerText = "Selecciona una fecha";
    hayError = true;
} else {
    const [año, mes, dia] = fecha.split('-').map(Number);

    // Crear fecha local sin desfase
    const fechaIngresada = new Date(año, mes - 1, dia, 0, 0, 0);

    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    // Permitir fecha de HOY o futura
    if (fechaIngresada < hoy) {
        fechaError.innerText = "La fecha no puede ser pasada.";
        hayError = true;
    }
}


    // =========================
    // VALIDACIÓN IMAGEN
    // =========================
    const imagenError = comprobarImagen(imagen);

    imagenError.forEach(error => {
        document.getElementById('imagenError').innerHTML += `<p>${error}</p>`;
        hayError = true;
    });

    // =========================
    // ENVIAR FORMULARIO
    // =========================
    if (!hayError) {
        formulariocontenido.submit();
    }
});

// ===============================
// FUNCION VALIDAR IMAGEN
// ===============================
function comprobarImagen(imagen) {
    let errores = [];

    if (imagen.files.length === 0) {
        errores.push("No seleccionaste ninguna imagen");
        return errores;
    }

    const archivo = imagen.files[0];

    if (archivo.type !== 'image/jpeg') {
        errores.push("La imagen debe ser JPEG");
    }

    if (archivo.size > 5 * 1024 * 1024) {
        errores.push("La imagen es demasiado grande (máx 5MB)");
    }

    return errores;
}

// ===============================
// CONTADOR DE CARACTERES
// ===============================
textarea.addEventListener('input', function () {
    contador.textContent = this.value.length;
});

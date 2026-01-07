const formularioTestimonio = document.getElementById('formularioTestimonio');

const noVacio = /^\s*\S.*$/;
const textarea = document.getElementById('contenido'); // Cambiado de 'testimonio' a 'contenido'
const contador = document.getElementById('contador');

formularioTestimonio.addEventListener('submit', (e) => {
    e.preventDefault();

    // Resetear errores
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    let hayErrores = false;

    const autor = document.getElementById('autor_id').value.trim(); // Cambiado de 'autor' a 'autor_id'
    const testimonio = document.getElementById('contenido').value.trim(); // Cambiado

    // Validaciones
    if (!noVacio.test(autor)) {
        document.getElementById('autorError').innerText = "Campo obligatorio";
        hayErrores = true;
    }

    if (!noVacio.test(testimonio)) {
        document.getElementById('testimonioError').innerText = "Campo obligatorio";
        hayErrores = true;
    }

    // Si hay errores, no enviamos
    if (hayErrores) return;

    // Si todo correcto, enviamos
    formularioTestimonio.submit();
});

// Contador de caracteres
textarea.addEventListener('input', function () {
    contador.textContent = this.value.length;
});

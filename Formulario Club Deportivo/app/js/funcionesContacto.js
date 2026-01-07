const formularioContacto = document.getElementById('formularioContacto');

const noVacio = /^\s*\S.*$/;
const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

formularioContacto.addEventListener('submit', (e) => {
    e.preventDefault();

    let errores = false; // <-- IMPORTANTE

    // Limpiar errores previos
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    // Campos
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const mensaje = document.getElementById('mensaje').value;

    // Validaciones
    if (!noVacio.test(nombre.trim()) || 
        !soloLetras.test(nombre.trim()) || 
        nombre.trim().length < 3) {

        document.getElementById('nombreError').innerText =
            "Introduce un nombre válido (solo letras, mínimo 3 caracteres)";
        errores = true;
    }

    if (!emailRegex.test(email.trim()) || email.trim().length > 255) {
        document.getElementById('emailError').innerText =
            "Introduce un correo electrónico válido";
        errores = true;
    }

    if (!noVacio.test(mensaje.trim()) || mensaje.trim().length < 5) {
        document.getElementById('mensajeError').innerText =
            "Escribe un mensaje (mínimo 5 caracteres)";
        errores = true;
    }

    // SI HAY ERRORES → BLOQUEA
    if (errores) {
        return;
    }

    // SI TODO ESTÁ BIEN → ENVIAR FORMULARIO
    formularioContacto.submit();
});

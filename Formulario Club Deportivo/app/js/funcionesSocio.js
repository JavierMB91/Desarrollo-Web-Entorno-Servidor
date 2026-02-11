const formularioSocio = document.getElementById('formularioSocio');

formularioSocio.addEventListener('submit', (e) => {
    e.preventDefault();

    // Resetear errores
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    let hayErrores = false;

    const nombre = document.getElementById('nombre').value.trim();
    const edad = document.getElementById('edad').value.trim();
    const password = document.getElementById('password').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const foto = document.getElementById('foto');

    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    const sinCaracteresEspeciales = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s]+$/;
    const soloLetrasNumerosGuiones = /^[A-Za-z0-9_]+$/;
    const telefonoEspana = /^\d{9}$/;

    // Validaciones
    if (nombre.length < 4 || nombre.length > 50 || !soloLetras.test(nombre)) {
        document.getElementById('nombreError').innerText = "Nombre no válido";
        hayErrores = true;
    }

    if (Number(edad) < 18) {
        document.getElementById('edadError').innerText = "Debes ser mayor de edad";
        hayErrores = true;
    }

    if (password.length < 8 || password.length > 20 || !soloLetrasNumerosGuiones.test(password)) {
        document.getElementById('passwordError').innerText = "Clave no válida";
        hayErrores = true;
    }

    if (!telefonoEspana.test(telefono)) {
        document.getElementById('telefonoError').innerText = "Teléfono no válido";
        hayErrores = true;
    }

    // Validación foto
    let fotoError = comprobarfoto(foto);
    if (fotoError.length > 0) {
        hayErrores = true;
        let fotoErrorBox = document.getElementById('fotoError');
        fotoErrorBox.innerHTML = "";
        fotoError.forEach(error => {
            fotoErrorBox.innerHTML += `<p>${error}</p>`;
        });
    }

    // Si hay errores, no envío
    if (hayErrores) return;

    // Si todo correcto, enviar
    formularioSocio.submit();
});

function comprobarfoto(foto) {
    let fotoError = [];

    if (foto.files.length === 0) {
        fotoError.push("No hay seleccionado ninguna foto");
        return fotoError;
    }

    if (foto.files[0].type !== 'image/jpeg') {
        fotoError.push("La foto debe ser un JPEG");
    }

    if (foto.files[0].size > 5 * 1024 * 1024) {
        fotoError.push("La foto es demasiado grande");
    }

    return fotoError;
}

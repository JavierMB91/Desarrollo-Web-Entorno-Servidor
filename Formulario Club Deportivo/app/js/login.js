const formularioLogin = document.getElementById('formularioLogin');

formularioLogin.addEventListener('submit', (e) => {
    e.preventDefault();

    // Resetear errores
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => span.innerText = "");

    let hayErrores = false;

    const nombre = document.getElementById('nombre').value.trim();
    const password = document.getElementById('password').value.trim();

    const soloLetrasNumerosGuiones = /^[A-Za-z0-9_]+$/;

    // Validaciones
    if (nombre.length < 4 || nombre.length > 20 || !soloLetrasNumerosGuiones.test(nombre)) {
        document.getElementById('nombreError').innerText = "nombre no válido (4-20 caracteres, letras, números o _)";
        hayErrores = true;
    }

    if (password.length < 8 || password.length > 20 || !soloLetrasNumerosGuiones.test(password)) {
        document.getElementById('passwordError').innerText = "Contraseña no válida (8-20 caracteres, letras, números o _)";
        hayErrores = true;
    }

    // Si hay errores, no envío
    if (hayErrores) return;

    // Si todo correcto, enviar
    formularioLogin.submit();
});

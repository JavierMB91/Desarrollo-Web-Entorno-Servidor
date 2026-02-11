const formularioSocio = document.querySelector("form");

// Expresiones regulares
const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,50}$/;
const soloEdad = /^[0-9]{1,3}$/;
const soloTelefono = /^[0-9]{9}$/; // teléfono espanol
const soloJPG = /\.(jpg|jpeg)$/i;

formularioSocio.addEventListener("submit", (e) => {
    e.preventDefault();

    let hayError = false;

    // Limpiar errores previos
    document.querySelectorAll(".error").forEach(span => span.innerText = "");

    // Inputs
    const nombre = document.getElementById("nombre").value.trim();
    const edad = document.getElementById("edad").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const foto = document.getElementById("foto");

    // ====================
    // VALIDACIÓN NOMBRE
    // ====================
    if (!soloLetras.test(nombre)) {
        document.getElementById("nombreError").innerText = 
            "Introduce un nombre válido (solo letras, 3-50 caracteres).";
        hayError = true;
    }

    // ====================
    // VALIDACIÓN EDAD
    // ====================
    if (!soloEdad.test(edad) || Number(edad) < 1 || Number(edad) > 100) {
        document.getElementById("edadError").innerText =
            "Introduce una edad válida entre 1 y 100.";
        hayError = true;
    }

    // ====================
    // VALIDACIÓN TELÉFONO
    // ====================
    if (!soloTelefono.test(telefono)) {
        document.getElementById("telefonoError").innerText =
            "Introduce un teléfono válido de 9 dígitos.";
        hayError = true;
    }

    // ====================
    // VALIDACIÓN FOTO (OPCIONAL)
    // ====================
    if (foto.files.length > 0) {

        const archivo = foto.files[0];

        // Validar extensión JPG
        if (!soloJPG.test(archivo.name)) {
            document.getElementById("fotoError").innerText =
                "La foto debe estar en formato JPG o JPEG.";
            hayError = true;
        }

        // Validar tamano máximo (5 MB)
        if (archivo.size > 5 * 1024 * 1024) {
            document.getElementById("fotoError").innerText =
                "La imagen es demasiado grande (máx. 5MB).";
            hayError = true;
        }
    }

    // ====================
    // ENVIAR FORMULARIO
    // ====================
    if (!hayError) {
        formularioSocio.submit();
    }
});

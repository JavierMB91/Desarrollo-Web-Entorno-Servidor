const formularioCita = document.getElementById('formularioCita');

formularioCita.addEventListener('submit', (e) => {
    e.preventDefault();

    let errores = false;

    // Limpiar errores anteriores
    let spanErrors = document.querySelectorAll('.error');
    spanErrors.forEach(span => {
        span.innerText = "";
    });

    const cliente = document.getElementById('cliente').value;
    const servicio = document.getElementById('servicio').value;
    const dia = document.getElementById('dia').value;
    const hora = document.getElementById('hora').value;

    // VALIDACIÓN CLIENTE
    if (!cliente) {
        document.getElementById('clienteError').innerText = "Selecciona un participante.";
        errores = true;
    }

    // VALIDACIÓN SERVICIO
    if (!servicio) {
        document.getElementById('servicioError').innerText = "Selecciona un libro o actividad.";
        errores = true;
    }

    // VALIDACIÓN FECHA
    if (!dia) {
        document.getElementById('diaError').innerText = "Selecciona una fecha.";
        errores = true;
    } else {
        const fechaIngresada = new Date(dia + "T00:00:00"); // asegurar formato ISO
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        if (fechaIngresada < hoy) { // < hoy en lugar de <= hoy
            document.getElementById('diaError').innerText = "La fecha debe ser hoy o posterior.";
            errores = true;
        }
    }


    // VALIDACIÓN HORA
    if (!hora) {
        document.getElementById('horaError').innerText = "Selecciona una hora.";
        errores = true;
    }

    // Si hay errores, no enviar
    if (errores) return;

    // Si todo está bien, enviar formulario
    formularioCita.submit();
});

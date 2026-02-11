document.addEventListener('DOMContentLoaded', () => {
    // ===== FORMULARIOS =====
    const formularios = document.querySelectorAll('form');
    formularios.forEach(form => {
        form.style.opacity = 0;
        form.style.transform = 'translateY(20px)';
        form.style.transition = 'all 0.5s ease';

        // Animar al cargar
        setTimeout(() => {
            form.style.opacity = 1;
            form.style.transform = 'translateY(0)';
        }, 100);
    });

    // ===== SECCIONES / CAMBIO DE PAGINA =====
    const secciones = document.querySelectorAll('.seccion'); // clase común para cada "página" o pestana
    secciones.forEach(sec => {
        sec.style.opacity = 0;
        sec.style.transition = 'opacity 0.5s ease';
    });

    function mostrarSeccion(indice) {
        secciones.forEach((sec, i) => {
            if (i === indice) {
                sec.style.opacity = 1;
                sec.style.pointerEvents = 'auto';
            } else {
                sec.style.opacity = 0;
                sec.style.pointerEvents = 'none';
            }
        });
    }

    // Inicialmente mostrar la primera sección
    mostrarSeccion(0);

    // Ejemplo de cambio de pestana con botones
    const botonesPestana = document.querySelectorAll('.btn-pestana');
    botonesPestana.forEach((btn, i) => {
        btn.addEventListener('click', () => {
            mostrarSeccion(i);
        });
    });
});

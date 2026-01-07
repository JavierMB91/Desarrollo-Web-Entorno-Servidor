const modal = document.getElementById('modalCancelar');
const cerrarModal = document.getElementById('cerrarModal');
const confirmarCancelar = document.getElementById('confirmarCancelar');

let urlCancelar = '';

// Delegación: escucha clicks en cualquier botón cancelar
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-cancelar')) {
        urlCancelar = e.target.getAttribute('data-url');
        modal.style.display = 'flex';
    }
});

cerrarModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

confirmarCancelar.addEventListener('click', () => {
    if (urlCancelar) {
        window.location.href = urlCancelar;
    }
});

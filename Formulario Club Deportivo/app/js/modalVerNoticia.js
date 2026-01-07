document.addEventListener('DOMContentLoaded', function() {
    // Obtener los elementos del DOM
    const modal = document.getElementById("miModal");
    const img = document.getElementById("imagenNoticia");
    const modalImg = document.getElementById("imagenGrande");
    const span = document.getElementsByClassName("cerrar-modal")[0];

    // Cuando el usuario hace clic en la imagen, abrir el modal
    img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
    }

    // Cuando el usuario hace clic en <span> (x) o en el fondo, cerrar el modal
    const cerrar = () => modal.style.display = "none";
    span.onclick = cerrar;
    modal.onclick = (event) => { if(event.target === modal) cerrar(); };
});
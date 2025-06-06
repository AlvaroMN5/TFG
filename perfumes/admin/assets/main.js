document.addEventListener("DOMContentLoaded", () => {
    const deleteLinks = document.querySelectorAll("a.delete");

    deleteLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            if (!confirm("¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.")) {
                e.preventDefault();
            }
        });
    });
});

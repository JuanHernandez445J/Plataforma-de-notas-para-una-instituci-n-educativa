let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let criterio_nombre = document.getElementById("criterio_nombre").value;
    let criterio_porcentaje = document.getElementById("criterio_porcentaje").value;
    let criterio_descripcion = document.getElementById("criterio_descripcion").value;

    if (criterio_nombre.trim() === "") {
        Swal.fire("Error", "Ingrese el nombre de la evaluación.", "error");
        return;
    }

    if (criterio_porcentaje.trim() === "" || isNaN(criterio_porcentaje) || criterio_porcentaje < 1 || criterio_porcentaje > 100) {
        Swal.fire("Error", "El porcentaje debe estar entre 1 y 100.", "error");
        return;
    }

    if (criterio_descripcion.trim() === "") {
        Swal.fire("Error", "Ingrese la descripción del criterio.", "error");
        return;
    }

    Swal.fire("Correcto", "Criterio creado correctamente.", "success");
});
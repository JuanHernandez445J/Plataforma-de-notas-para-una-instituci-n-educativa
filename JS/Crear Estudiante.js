let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let estu_id = document.getElementById("estu_id").value;
    let estu_foto = document.getElementById("estu_foto").files[0];
    let estu_codigo = document.getElementById("estu_codigo").value;
    let estu_nombre = document.getElementById("estu_nombre").value;
    let estu_identificacion = document.getElementById("estu_identificacion").value;
    let estu_jornada = document.getElementById("estu_jornada").value;
    let estu_grado = document.getElementById("estu_grado").value;
    let estu_inasistencias = document.getElementById("estu_inasistencias").value;
    let estu_observaciones = document.getElementById("estu_observaciones").value;

    if (estu_id.trim() === "" || isNaN(estu_id) || estu_id <= 0) {
        Swal.fire("Error", "Ingrese un ID válido.", "error");
        return;
    }

    if (!estu_foto) {
        Swal.fire("Error", "Seleccione una foto.", "error");
        return;
    }

    if (estu_codigo.trim() === "" || estu_nombre.trim() === "") {
        Swal.fire("Error", "Ingrese el código y el nombre.", "error");
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(estu_nombre.trim())) {
        Swal.fire("Error", "El nombre solo debe contener letras.", "error");
        return;
    }

    if (estu_identificacion.trim() === "" || isNaN(estu_identificacion)) {
        Swal.fire("Error", "Ingrese una identificación válida.", "error");
        return;
    }

    if (estu_jornada.trim() === "" || estu_grado.trim() === "") {
        Swal.fire("Error", "Seleccione la jornada y el grado.", "error");
        return;
    }

    if (estu_inasistencias.trim() === "" || isNaN(estu_inasistencias) || estu_inasistencias < 0) {
        Swal.fire("Error", "Ingrese unas inasistencias válidas.", "error");
        return;
    }

    if (estu_observaciones.trim() === "") {
        Swal.fire("Error", "Ingrese una observación.", "error");
        return;
    }

    Swal.fire("Correcto", "Estudiante creado correctamente.", "success");
});
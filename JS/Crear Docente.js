let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let doce_foto = document.getElementById("doce_foto").files[0];
    let doce_id = document.getElementById("doce_id").value;
    let doce_nombre = document.getElementById("doce_nombre").value;
    let doce_identificacion = document.getElementById("doce_identificacion").value;
    let doce_direccion = document.getElementById("doce_direccion").value;
    let doce_telefono = document.getElementById("doce_telefono").value;
    let doce_email = document.getElementById("doce_email").value;
    let doce_especialidad = document.getElementById("doce_especialidad").value;

    if (doce_id.trim() === "" || isNaN(doce_id) || doce_id <= 0) {
        Swal.fire("Error", "Ingrese un ID válido.", "error");
        return;
    }

    if (!doce_foto) {
        Swal.fire("Error", "Seleccione una foto.", "error");
        return;
    }

    if (doce_nombre.trim() === "" || !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(doce_nombre.trim())) {
        Swal.fire("Error", "Ingrese un nombre válido.", "error");
        return;
    }

    if (doce_identificacion.trim() === "" || isNaN(doce_identificacion)) {
        Swal.fire("Error", "Ingrese una identificación válida.", "error");
        return;
    }

    if (doce_direccion.trim() === "" || doce_telefono.trim() === "") {
        Swal.fire("Error", "Ingrese la dirección y el teléfono.", "error");
        return;
    }

    if (doce_email.trim() === "" || !doce_email.includes("@")) {
        Swal.fire("Error", "Ingrese un correo electrónico válido.", "error");
        return;
    }

    if (doce_especialidad.trim() === "") {
        Swal.fire("Error", "Ingrese la especialidad del docente.", "error");
        return;
    }

    Swal.fire("Correcto", "Docente creado correctamente.", "success");
});

let formulario = document.getElementById("formularioDocente");

formulario.addEventListener("submit", function(event) {

    event.preventDefault();

    let doce_foto = document.getElementById("doce_foto");
    let doce_nombre = document.getElementById("doce_nombre").value;
    let doce_apellido = document.getElementById("doce_apellido").value;
    let doce_identificacion = document.getElementById("doce_identificacion").value;
    let doce_direccion = document.getElementById("doce_direccion").value;
    let doce_telefono = document.getElementById("doce_telefono").value;
    let doce_email = document.getElementById("doce_email").value;
    let doce_especialidad = document.getElementById("doce_especialidad").value;

    if (doce_foto.files.length === 0) {
        Swal.fire("Error", "Ingrese la foto del docente.", "error");
        return;
    }

    if (doce_nombre.trim() === "" || doce_apellido.trim() === "") {
        Swal.fire("Error", "Ingrese el nombre y apellido.", "error");
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(doce_nombre.trim())) {
        Swal.fire("Error", "El nombre solo debe contener letras.", "error");
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(doce_apellido.trim())) {
        Swal.fire("Error", "El apellido solo debe contener letras.", "error");
        return;
    }

    if (doce_identificacion.trim() === "" || isNaN(doce_identificacion)) {
        Swal.fire("Error", "Ingrese una identificación válida.", "error");
        return;
    }

    if (doce_direccion.trim() === "") {
        Swal.fire("Error", "Ingrese la dirección.", "error");
        return;
    }

    if (doce_telefono.trim() === "" || isNaN(doce_telefono)) {
        Swal.fire("Error", "Ingrese un teléfono válido.", "error");
        return;
    }

    if (doce_email.trim() === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(doce_email.trim())) {
        Swal.fire("Error", "Ingrese un correo electrónico válido.", "error");
        return;
    }

    if (doce_especialidad.trim() === "") {
        Swal.fire("Error", "Ingrese la especialidad.", "error");
        return;
    }

    let datos = new FormData(formulario);

    fetch("../PHP/Crear-docente.php", {
        method: "POST",
        body: datos
    })
    .then(function(respuesta) {
        return respuesta.json();
    })
    .then(function(resultado) {

        if (resultado.exito) {
            Swal.fire("Correcto", resultado.mensaje, "success");
            formulario.reset();
        } else {
            Swal.fire("Error", resultado.mensaje, "error");
        }

    })
    .catch(function(error) {
        console.log("Error:", error);
        Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    });

});

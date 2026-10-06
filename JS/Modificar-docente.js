let formulario = document.getElementById("formularioDocente");
let buscar = document.getElementById("buscar");
let datosDocente = document.getElementById("datosDocente");

datosDocente.style.display = "none";


// BUSCAR DOCENTE
buscar.addEventListener("click", function() {

    let doce_id = document.getElementById("doce_id").value;

    if (doce_id.trim() === "" || isNaN(doce_id) || doce_id <= 0) {
        Swal.fire(
            "Error",
            "Ingrese un ID de docente válido.",
            "error"
        );
        return;
    }

    fetch("../PHP/Buscar-docente.php?id=" + doce_id)
        .then(function(respuesta) {
            return respuesta.json();
        })
        .then(function(docente) {

            console.log(docente);

            if (docente.exito) {

                document.getElementById("doce_foto").value = docente.doce_foto;
                document.getElementById("doce_nombre").value = docente.doce_nombre;
                document.getElementById("doce_apellido").value = docente.doce_apellido;
                document.getElementById("doce_identificacion").value = docente.doce_identificacion;
                document.getElementById("doce_direccion").value = docente.doce_direccion;
                document.getElementById("doce_telefono").value = docente.doce_telefono;
                document.getElementById("doce_email").value = docente.doce_email;
                document.getElementById("doce_especialidad").value = docente.doce_especialidad;

                datosDocente.style.display = "block";

            } else {

                Swal.fire(
                    "Error",
                    "No existe un docente con ese ID.",
                    "error"
                );

                datosDocente.style.display = "none";
            }
        })
        .catch(function(error) {

            console.log("Error:", error);

            Swal.fire(
                "Error",
                "No se pudo conectar con el servidor.",
                "error"
            );
        });
});


// MODIFICAR DOCENTE
formulario.addEventListener("submit", function(event) {

    event.preventDefault();

    let doce_id = document.getElementById("doce_id").value;
    let doce_foto = document.getElementById("doce_foto").value;
    let doce_nombre = document.getElementById("doce_nombre").value;
    let doce_apellido = document.getElementById("doce_apellido").value;
    let doce_identificacion = document.getElementById("doce_identificacion").value;
    let doce_direccion = document.getElementById("doce_direccion").value;
    let doce_telefono = document.getElementById("doce_telefono").value;
    let doce_email = document.getElementById("doce_email").value;
    let doce_especialidad = document.getElementById("doce_especialidad").value;


    // VALIDACIONES

    if (doce_foto.trim() === "") {
        Swal.fire(
            "Error",
            "Ingrese la foto del docente.",
            "error"
        );
        return;
    }

    if (doce_nombre.trim() === "" || doce_apellido.trim() === "") {
        Swal.fire(
            "Error",
            "Ingrese el nombre y apellido.",
            "error"
        );
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(doce_nombre.trim())) {
        Swal.fire(
            "Error",
            "El nombre solo debe contener letras.",
            "error"
        );
        return;
    }

    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(doce_apellido.trim())) {
        Swal.fire(
            "Error",
            "El apellido solo debe contener letras.",
            "error"
        );
        return;
    }

    if (doce_identificacion.trim() === "" || isNaN(doce_identificacion)) {
        Swal.fire(
            "Error",
            "Ingrese una identificación válida.",
            "error"
        );
        return;
    }

    if (doce_direccion.trim() === "") {
        Swal.fire(
            "Error",
            "Ingrese la dirección.",
            "error"
        );
        return;
    }

    if (doce_telefono.trim() === "" || isNaN(doce_telefono)) {
        Swal.fire(
            "Error",
            "Ingrese un teléfono válido.",
            "error"
        );
        return;
    }

    if (doce_email.trim() === "" || !doce_email.includes("@")) {
        Swal.fire(
            "Error",
            "Ingrese un correo electrónico válido.",
            "error"
        );
        return;
    }

    if (doce_especialidad.trim() === "") {
        Swal.fire(
            "Error",
            "Ingrese la especialidad.",
            "error"
        );
        return;
    }

    let datos = new FormData();

    datos.append("doce_id", doce_id);
    datos.append("doce_foto", doce_foto);
    datos.append("doce_nombre", doce_nombre);
    datos.append("doce_apellido", doce_apellido);
    datos.append("doce_identificacion", doce_identificacion);
    datos.append("doce_direccion", doce_direccion);
    datos.append("doce_telefono", doce_telefono);
    datos.append("doce_email", doce_email);
    datos.append("doce_especialidad", doce_especialidad);


    fetch("../PHP/Modificar-docente.php", {
        method: "POST",
        body: datos
    })
        .then(function(respuesta) {
            return respuesta.json();
        })
        .then(function(resultado) {

            if (resultado.exito) {

                Swal.fire(
                    "Correcto",
                    "Docente modificado correctamente.",
                    "success"
                );

            } else {

                Swal.fire(
                    "Error",
                    resultado.mensaje,
                    "error"
                );
            }
        })
        .catch(function(error) {

            console.log("Error:", error);

            Swal.fire(
                "Error",
                "No se pudo conectar con el servidor.",
                "error"
            );
        });
});
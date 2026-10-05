let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {

    event.preventDefault();

    let estu_foto = document.getElementById("estu_foto").value;
    let estu_nombre = document.getElementById("estu_nombre").value;
    let estu_apellido = document.getElementById("estu_apellido").value;
    let estu_identificacion = document.getElementById("estu_identificacion").value;
    let estu_gru_id = document.getElementById("estu_gru_id").value;
    let estu_acud_id = document.getElementById("estu_acud_id").value;
    let estu_observaciones = document.getElementById("estu_observaciones").value;


    if (estu_foto.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese la foto del estudiante.",
            "error"
        );

        return;
    }


    if (estu_nombre.trim() === "" || estu_apellido.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el nombre y el apellido.",
            "error"
        );

        return;
    }


    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(estu_nombre.trim())) {

        Swal.fire(
            "Error",
            "El nombre solo debe contener letras.",
            "error"
        );

        return;
    }


    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(estu_apellido.trim())) {

        Swal.fire(
            "Error",
            "El apellido solo debe contener letras.",
            "error"
        );

        return;
    }


    if (estu_identificacion.trim() === "" || isNaN(estu_identificacion)) {

        Swal.fire(
            "Error",
            "Ingrese una identificación válida.",
            "error"
        );

        return;
    }


    if (estu_gru_id.trim() === "" || isNaN(estu_gru_id) || estu_gru_id <= 0) {

        Swal.fire(
            "Error",
            "Ingrese un grupo válido.",
            "error"
        );

        return;
    }


    if (estu_acud_id.trim() === "" || isNaN(estu_acud_id) || estu_acud_id <= 0) {

        Swal.fire(
            "Error",
            "Ingrese un acudiente válido.",
            "error"
        );

        return;
    }


    let datos = new FormData();

    datos.append("estu_foto", estu_foto);
    datos.append("estu_nombre", estu_nombre);
    datos.append("estu_apellido", estu_apellido);
    datos.append("estu_identificacion", estu_identificacion);
    datos.append("estu_gru_id", estu_gru_id);
    datos.append("estu_acud_id", estu_acud_id);
    datos.append("estu_observaciones", estu_observaciones);


    fetch("../PHP/Crear-estudiante.php", {

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
                "Estudiante creado correctamente.",
                "success"
            );

            formulario.reset();

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
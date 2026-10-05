let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {

    event.preventDefault();

    let acud_parentesco = document.getElementById("acud_parentesco").value;
    let acud_nombre = document.getElementById("acud_nombre").value;
    let acud_apellido = document.getElementById("acud_apellido").value;
    let acud_identificacion = document.getElementById("acud_identificacion").value;
    let acud_direccion = document.getElementById("acud_direccion").value;
    let acud_telefono = document.getElementById("acud_telefono").value;
    let acud_email = document.getElementById("acud_email").value;


    if (acud_parentesco.trim() === "") {

        Swal.fire("Error", "Ingrese el parentesco.", "error");

        return;
    }


    if (acud_nombre.trim() === "" || acud_apellido.trim() === "") {

        Swal.fire("Error", "Ingrese el nombre y el apellido.", "error");

        return;
    }


    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(acud_nombre.trim())) {

        Swal.fire("Error", "El nombre solo debe contener letras.", "error");

        return;
    }


    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(acud_apellido.trim())) {

        Swal.fire("Error", "El apellido solo debe contener letras.", "error");

        return;
    }


    if (acud_identificacion.trim() === "" || isNaN(acud_identificacion)) {

        Swal.fire("Error", "Ingrese una identificación válida.", "error");

        return;
    }


    if (acud_direccion.trim() === "") {

        Swal.fire("Error", "Ingrese la dirección.", "error");

        return;
    }


    if (acud_telefono.trim() === "" || isNaN(acud_telefono)) {

        Swal.fire("Error", "Ingrese un teléfono válido.", "error");

        return;
    }


    if (acud_email.trim() === "" || !acud_email.includes("@")) {

        Swal.fire("Error", "Ingrese un correo electrónico válido.", "error");

        return;
    }


    let datos = new FormData();

    datos.append("acud_parentesco", acud_parentesco);
    datos.append("acud_nombre", acud_nombre);
    datos.append("acud_apellido", acud_apellido);
    datos.append("acud_identificacion", acud_identificacion);
    datos.append("acud_direccion", acud_direccion);
    datos.append("acud_telefono", acud_telefono);
    datos.append("acud_email", acud_email);


    fetch("../PHP/Crear-acudiente.php", {

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
                "Acudiente creado correctamente.",
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
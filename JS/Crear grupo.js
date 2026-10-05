let formulario = document.getElementById("formularioGrupo");

formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let gru_jornada = document.getElementById("gru_jornada").value;
    let gru_nombre = document.getElementById("gru_nombre").value;


    if (gru_jornada.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese la jornada del grupo.",
            "error"
        );

        return;
    }


    if (gru_nombre.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el nombre del grupo.",
            "error"
        );

        return;
    }


    let datos = new FormData();

    datos.append("gru_jornada", gru_jornada);
    datos.append("gru_nombre", gru_nombre);


    fetch("../PHP/Crear-grupo.php", {

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
                "Grupo creado correctamente.",
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
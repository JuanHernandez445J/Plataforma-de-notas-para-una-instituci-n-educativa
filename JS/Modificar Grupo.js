let formularioBuscar = document.getElementById("formularioBuscar");

let formularioModificar = document.getElementById("formularioModificar");


formularioBuscar.addEventListener("submit", function(event) {

    event.preventDefault();


    let gru_id = document.getElementById("gru_id").value;


    if (gru_id.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el ID del grupo.",
            "error"
        );

        return;
    }


    fetch("../PHP/Buscar-grupo.php?id=" + gru_id)

        .then(function(respuesta) {

            return respuesta.json();

        })

        .then(function(grupo) {

            if (grupo.exito) {

                document.getElementById("gru_jornada").value = grupo.gru_jornada;

                document.getElementById("gru_nombre").value = grupo.gru_nombre;


                formularioModificar.style.display = "block";

            } else {

                formularioModificar.style.display = "none";

                Swal.fire(
                    "No encontrado",
                    "No existe un grupo con ese ID.",
                    "warning"
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


formularioModificar.addEventListener("submit", function(event) {

    event.preventDefault();


    let gru_id = document.getElementById("gru_id").value;

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


    datos.append("gru_id", gru_id);

    datos.append("gru_jornada", gru_jornada);

    datos.append("gru_nombre", gru_nombre);


    fetch("../PHP/Modificar-grupo.php", {

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
                "Grupo modificado correctamente.",
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
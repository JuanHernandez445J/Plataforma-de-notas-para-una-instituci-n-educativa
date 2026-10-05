let formulario = document.getElementById("formularioMateria");

let botonBuscar = document.getElementById("buscarMateria");


botonBuscar.addEventListener("click", function() {

    let asig_id = document.getElementById("asig_id").value;


    if (asig_id.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el ID de la materia.",
            "error"
        );

        return;
    }


    fetch("../PHP/Buscar-materia.php?id=" + asig_id)

        .then(function(respuesta) {

            return respuesta.json();

        })

        .then(function(materia) {

            if (materia.exito) {

                document.getElementById("asig_gru_id").value =
                    materia.asig_gru_id;

                document.getElementById("asig_nombre").value =
                    materia.asig_nombre;

                document.getElementById("asig_doce_id").value =
                    materia.asig_doce_id;

                document.getElementById("asig_logro").value =
                    materia.asig_logro;

                document.getElementById("asig_intensidad").value =
                    materia.asig_intensidad;


                document.getElementById("camposMateria").style.display =
                    "block";

            } else {

                Swal.fire(
                    "No encontrado",
                    "No existe una materia con ese ID.",
                    "error"
                );

                document.getElementById("camposMateria").style.display =
                    "none";
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


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let asig_id = document.getElementById("asig_id").value;
    let asig_gru_id = document.getElementById("asig_gru_id").value;
    let asig_nombre = document.getElementById("asig_nombre").value;
    let asig_doce_id = document.getElementById("asig_doce_id").value;
    let asig_logro = document.getElementById("asig_logro").value;
    let asig_intensidad = document.getElementById("asig_intensidad").value;


    if (asig_gru_id.trim() === "" || isNaN(asig_gru_id)) {

        Swal.fire(
            "Error",
            "Ingrese un ID de grupo válido.",
            "error"
        );

        return;
    }


    if (asig_nombre.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el nombre de la materia.",
            "error"
        );

        return;
    }


    if (asig_doce_id.trim() === "" || isNaN(asig_doce_id)) {

        Swal.fire(
            "Error",
            "Ingrese un ID de docente válido.",
            "error"
        );

        return;
    }


    if (asig_logro.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el logro de la materia.",
            "error"
        );

        return;
    }


    if (asig_intensidad.trim() === "" || isNaN(asig_intensidad)) {

        Swal.fire(
            "Error",
            "Ingrese una intensidad válida.",
            "error"
        );

        return;
    }


    let datos = new FormData();


    datos.append("asig_id", asig_id);
    datos.append("asig_gru_id", asig_gru_id);
    datos.append("asig_nombre", asig_nombre);
    datos.append("asig_doce_id", asig_doce_id);
    datos.append("asig_logro", asig_logro);
    datos.append("asig_intensidad", asig_intensidad);


    fetch("../PHP/Modificar-materia.php", {

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
                "La materia fue modificada correctamente.",
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
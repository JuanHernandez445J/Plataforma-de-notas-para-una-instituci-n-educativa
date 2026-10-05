let formulario = document.getElementById("formularioEliminar");

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

                document.getElementById("id").textContent =
                    materia.asig_id;

                document.getElementById("grupo").textContent =
                    materia.asig_gru_id;

                document.getElementById("nombre").textContent =
                    materia.asig_nombre;

                document.getElementById("docente").textContent =
                    materia.asig_doce_id;

                document.getElementById("logro").textContent =
                    materia.asig_logro;

                document.getElementById("intensidad").textContent =
                    materia.asig_intensidad;


                document.getElementById("datosMateria").style.display =
                    "block";

                document.getElementById("eliminar").style.display =
                    "block";

            } else {

                Swal.fire(
                    "No encontrado",
                    "No existe una materia con ese ID.",
                    "error"
                );


                document.getElementById("datosMateria").style.display =
                    "none";

                document.getElementById("eliminar").style.display =
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


    Swal.fire({

        title: "¿Está seguro?",

        text: "La materia será eliminada de la base de datos.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar"

    }).then(function(resultado) {

        if (resultado.isConfirmed) {

            let datos = new FormData();

            datos.append("asig_id", asig_id);


            fetch("../PHP/Eliminar-materia.php", {

                method: "POST",

                body: datos

            })

            .then(function(respuesta) {

                return respuesta.json();

            })

            .then(function(resultado) {

                if (resultado.exito) {

                    Swal.fire(
                        "Eliminada",
                        "La materia fue eliminada correctamente.",
                        "success"
                    );


                    formulario.reset();

                    document.getElementById("datosMateria").style.display =
                        "none";

                    document.getElementById("eliminar").style.display =
                        "none";

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

        }

    });

});
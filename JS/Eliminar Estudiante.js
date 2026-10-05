let formulario = document.getElementById("formularioEstudiante");
let buscar = document.getElementById("buscar");
let datosEstudiante = document.getElementById("datosEstudiante");

datosEstudiante.style.display = "none";


buscar.addEventListener("click", function() {

    let estu_id = document.getElementById("estu_id").value;

    if (estu_id.trim() === "" || isNaN(estu_id) || estu_id <= 0) {

        Swal.fire(
            "Error",
            "Ingrese un ID de estudiante válido.",
            "error"
        );

        return;
    }


    fetch("../PHP/Buscar-estudiante.php?id=" + estu_id)

        .then(function(respuesta) {
            return respuesta.json();
        })

        .then(function(estudiante) {

            if (estudiante.exito) {

                document.getElementById("estu_foto").textContent =
                    estudiante.estu_foto;

                document.getElementById("estu_nombre").textContent =
                    estudiante.estu_nombre;

                document.getElementById("estu_apellido").textContent =
                    estudiante.estu_apellido;

                document.getElementById("estu_identificacion").textContent =
                    estudiante.estu_identificacion;

                document.getElementById("estu_gru_id").textContent =
                    estudiante.estu_gru_id;

                document.getElementById("estu_acud_id").textContent =
                    estudiante.estu_acud_id;

                document.getElementById("estu_observaciones").textContent =
                    estudiante.estu_observaciones;

                datosEstudiante.style.display = "block";

            } else {

                Swal.fire(
                    "Error",
                    "No existe un estudiante con ese ID.",
                    "error"
                );

                datosEstudiante.style.display = "none";
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

    let estu_id = document.getElementById("estu_id").value;


    Swal.fire({
        title: "¿Está seguro?",
        text: "El estudiante será eliminado de la base de datos.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    })

    .then(function(resultado) {

        if (resultado.isConfirmed) {

            let datos = new FormData();

            datos.append("estu_id", estu_id);


            fetch("../PHP/Eliminar-estudiante.php", {
                method: "POST",
                body: datos
            })

            .then(function(respuesta) {
                return respuesta.json();
            })

            .then(function(resultado) {

                if (resultado.exito) {

                    Swal.fire(
                        "Eliminado",
                        "El estudiante fue eliminado correctamente.",
                        "success"
                    );

                    formulario.reset();

                    datosEstudiante.style.display = "none";

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
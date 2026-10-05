let formulario = document.getElementById("formularioGrupo");

let informacion = document.getElementById("informacion");

let btnEliminar = document.getElementById("btnEliminar");


formulario.addEventListener("submit", function(event) {

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

                document.getElementById("mostrar_id").textContent = grupo.gru_id;

                document.getElementById("mostrar_jornada").textContent = grupo.gru_jornada;

                document.getElementById("mostrar_nombre").textContent = grupo.gru_nombre;


                informacion.style.display = "block";

            } else {

                informacion.style.display = "none";

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


btnEliminar.addEventListener("click", function() {

    Swal.fire({

        title: "¿Está seguro?",

        text: "El grupo será eliminado de la base de datos.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar"

    }).then(function(resultado) {

        if (resultado.isConfirmed) {


            let gru_id = document.getElementById("gru_id").value;

            let datos = new FormData();

            datos.append("gru_id", gru_id);


            fetch("../PHP/Eliminar-grupo.php", {

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
                        "El grupo fue eliminado correctamente.",
                        "success"
                    );


                    formulario.reset();

                    informacion.style.display = "none";

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
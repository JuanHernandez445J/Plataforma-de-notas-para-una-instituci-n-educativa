let formulario = document.getElementById("formularioUsuario");

let informacion = document.getElementById("informacion");

let btnEliminar = document.getElementById("btnEliminar");


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let usua_id = document.getElementById("usua_id").value;


    if (usua_id.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el ID del usuario.",
            "error"
        );

        return;
    }


    fetch("../PHP/Buscar-usuario.php?id=" + usua_id)

        .then(function(respuesta) {

            return respuesta.json();

        })

        .then(function(usuario) {

            if (usuario.exito) {

                document.getElementById("mostrar_id").textContent = usuario.usua_id;

                document.getElementById("mostrar_usuario").textContent = usuario.usua_usuario;

                document.getElementById("mostrar_rol").textContent = usuario.usua_rol;


                if (usuario.usua_activo == 1) {

                    document.getElementById("mostrar_estado").textContent = "Activo";

                } else {

                    document.getElementById("mostrar_estado").textContent = "Inactivo";

                }


                informacion.style.display = "block";

            } else {

                informacion.style.display = "none";

                Swal.fire(
                    "No encontrado",
                    "No existe un usuario con ese ID.",
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

        text: "El usuario será eliminado de la base de datos.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar"

    }).then(function(resultado) {

        if (resultado.isConfirmed) {


            let usua_id = document.getElementById("usua_id").value;


            let datos = new FormData();

            datos.append("usua_id", usua_id);


            fetch("../PHP/Eliminar-usuario.php", {

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
                        "El usuario fue eliminado correctamente.",
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
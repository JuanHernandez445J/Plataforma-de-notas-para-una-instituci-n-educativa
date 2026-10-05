let formulario = document.querySelector("form");

let botonBuscar = document.getElementById("buscar");

let botonEliminar = document.getElementById("eliminar");

let informacion = document.getElementById("informacion");


informacion.style.display = "none";

botonEliminar.style.display = "none";


botonBuscar.addEventListener("click", function() {

    let acud_id = document.getElementById("acud_id").value;


    if (acud_id.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el ID del acudiente.",
            "error"
        );

        return;
    }


    fetch("../PHP/Buscar-acudiente.php?id=" + acud_id)

        .then(function(respuesta) {

            return respuesta.json();

        })

        .then(function(acudiente) {

            if (acudiente.exito) {

                document.getElementById("parentesco").textContent = acudiente.acud_parentesco;

                document.getElementById("nombre").textContent = acudiente.acud_nombre;

                document.getElementById("apellido").textContent = acudiente.acud_apellido;

                document.getElementById("identificacion").textContent = acudiente.acud_identificacion;

                document.getElementById("direccion").textContent = acudiente.acud_direccion;

                document.getElementById("telefono").textContent = acudiente.acud_telefono;

                document.getElementById("email").textContent = acudiente.acud_email;


                informacion.style.display = "block";

                botonEliminar.style.display = "block";

            } else {

                informacion.style.display = "none";

                botonEliminar.style.display = "none";

                Swal.fire(
                    "Error",
                    "No se encontró el acudiente.",
                    "error"
                );

            }

        })

        .catch(function(error) {

            console.log("Error:", error);

        });

});


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let acud_id = document.getElementById("acud_id").value;


    if (acud_id.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el ID del acudiente.",
            "error"
        );

        return;
    }


    Swal.fire({

        title: "¿Está seguro?",

        text: "Esta acción eliminará el acudiente.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar"

    }).then(function(resultado) {

        if (resultado.isConfirmed) {

            let datos = new FormData();

            datos.append("acud_id", acud_id);


            fetch("../PHP/Eliminar-acudiente.php", {

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
                        "El acudiente fue eliminado correctamente.",
                        "success"
                    );


                    formulario.reset();

                    informacion.style.display = "none";

                    botonEliminar.style.display = "none";

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

            });

        }

    });

});
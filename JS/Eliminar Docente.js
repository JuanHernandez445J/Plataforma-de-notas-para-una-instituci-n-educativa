let formulario = document.getElementById("formularioEliminar");
let botonBuscar = document.getElementById("buscarDocente");

botonBuscar.addEventListener("click", function() {

    let doce_id = document.getElementById("doce_id").value;

    if (doce_id.trim() === "") {
        Swal.fire("Error", "Ingrese el ID del docente.", "error");
        return;
    }

    fetch("../PHP/Buscar-docente.php?id=" + doce_id)
        .then(function(respuesta) {
            return respuesta.json();
        })
        .then(function(docente) {

            if (docente.exito) {

                document.getElementById("foto").textContent = docente.doce_foto;
                document.getElementById("nombre").textContent = docente.doce_nombre;
                document.getElementById("apellido").textContent = docente.doce_apellido;
                document.getElementById("identificacion").textContent = docente.doce_identificacion;
                document.getElementById("direccion").textContent = docente.doce_direccion;
                document.getElementById("telefono").textContent = docente.doce_telefono;
                document.getElementById("email").textContent = docente.doce_email;
                document.getElementById("especialidad").textContent = docente.doce_especialidad;

                document.getElementById("datosDocente").style.display = "block";
                document.getElementById("eliminar").style.display = "block";

            } else {

                Swal.fire(
                    "No encontrado",
                    "No existe un docente con ese ID.",
                    "error"
                );

                document.getElementById("datosDocente").style.display = "none";
                document.getElementById("eliminar").style.display = "none";
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

    let doce_id = document.getElementById("doce_id").value;

    Swal.fire({
        title: "¿Está seguro?",
        text: "El docente será eliminado de la base de datos.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(function(resultado) {

        if (resultado.isConfirmed) {

            let datos = new FormData();

            datos.append("doce_id", doce_id);

            fetch("../PHP/Eliminar-docente.php", {
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
                        "El docente fue eliminado correctamente.",
                        "success"
                    );

                    formulario.reset();

                    document.getElementById("datosDocente").style.display = "none";
                    document.getElementById("eliminar").style.display = "none";

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
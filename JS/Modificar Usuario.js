let formularioBuscar = document.getElementById("formularioBuscar");

let formularioModificar = document.getElementById("formularioModificar");


formularioBuscar.addEventListener("submit", function(event) {

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

                document.getElementById("usua_usuario").value = usuario.usua_usuario;

                document.getElementById("usua_clave").value = usuario.usua_clave;

                document.getElementById("usua_rol").value = usuario.usua_rol;

                document.getElementById("usua_activo").value = usuario.usua_activo;


                formularioModificar.style.display = "block";

            } else {

                formularioModificar.style.display = "none";

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


formularioModificar.addEventListener("submit", function(event) {

    event.preventDefault();


    let usua_id = document.getElementById("usua_id").value;

    let usua_usuario = document.getElementById("usua_usuario").value;

    let usua_clave = document.getElementById("usua_clave").value;

    let usua_rol = document.getElementById("usua_rol").value;

    let usua_activo = document.getElementById("usua_activo").value;


    if (usua_usuario.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el nombre de usuario.",
            "error"
        );

        return;
    }


    if (usua_clave.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese una contraseña.",
            "error"
        );

        return;
    }


    if (usua_rol.trim() === "") {

        Swal.fire(
            "Error",
            "Seleccione un rol.",
            "error"
        );

        return;
    }


    if (usua_activo.trim() === "") {

        Swal.fire(
            "Error",
            "Seleccione el estado del usuario.",
            "error"
        );

        return;
    }


    let datos = new FormData();


    datos.append("usua_id", usua_id);

    datos.append("usua_usuario", usua_usuario);

    datos.append("usua_clave", usua_clave);

    datos.append("usua_rol", usua_rol);

    datos.append("usua_activo", usua_activo);


    fetch("../PHP/Modificar-usuario.php", {

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
                "Usuario modificado correctamente.",
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
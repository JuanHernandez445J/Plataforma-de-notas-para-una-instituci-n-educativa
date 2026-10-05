let formulario = document.getElementById("formularioUsuario");


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


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


    datos.append("usua_usuario", usua_usuario);

    datos.append("usua_clave", usua_clave);

    datos.append("usua_rol", usua_rol);

    datos.append("usua_activo", usua_activo);


    fetch("../PHP/Crear-usuario.php", {

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
                "Usuario creado correctamente.",
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
let formulario = document.getElementById("formularioLogin");

formulario.addEventListener("submit", function(event) {

    event.preventDefault();

    let usua_usuario = document.getElementById("correo").value;
    let usua_clave = document.getElementById("contraseña").value;

    if (usua_usuario.trim() === "") {
        Swal.fire("Error", "Ingrese su usuario.", "error");
        return;
    }

    if (usua_clave.trim() === "") {
        Swal.fire("Error", "Ingrese su contraseña.", "error");
        return;
    }

    let datos = new FormData();

    datos.append("usua_usuario", usua_usuario);
    datos.append("usua_clave", usua_clave);

    fetch("../PHP/Login.php", {
        method: "POST",
        body: datos
    })

    .then(function(respuesta) {

        return respuesta.text();

    })

    .then(function(texto) {

        console.log("Respuesta de PHP:", texto);

        let resultado = JSON.parse(texto);

        if (resultado.exito) {

            Swal.fire({
                title: "Bienvenido",
                text: "Inicio de sesión correcto.",
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            }).then(function() {

                if (resultado.rol === "estudiante") {
                    window.location.href = "../Modulos/Home_estudiante.html";
                }

                else if (resultado.rol === "docente") {
                    window.location.href = "../Modulos/Home_docente.html";
                }

                else if (resultado.rol === "admin") {
                    window.location.href = "../Modulos/Home_Admin.html";
                }

            });

        } else {

            Swal.fire(
                "Error",
                resultado.mensaje,
                "error"
            );

        }

    })

    .catch(function(error) {

        console.log("Error completo:", error);

        Swal.fire(
            "Error",
            "No se pudo conectar al servidor.",
            "error"
        );

    });

});
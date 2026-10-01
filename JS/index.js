let usuarios = [
    {
        correo: "estudiante1@correo.com",
        contraseña: "1234",
        rol: "estudiante"
    },

    {
        correo: "estudiante2@correo.com",
        contraseña: "5678",
        rol: "estudiante"
    },

    {
        correo: "profesor1@correo.com",
        contraseña: "1234",
        rol: "docente"
    },

    {
        correo: "profesor2@correo.com",
        contraseña: "5678",
        rol: "docente"
    },

    {
        correo: "admin1@correo.com",
        contraseña: "1234",
        rol: "admin"
    },

    {
        correo: "admin2@correo.com",
        contraseña: "5678",
        rol: "admin"
    }
];


let formulario = document.getElementById("formularioLogin");


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let correo = document.getElementById("correo").value;

    let contraseña = document.getElementById("contraseña").value;


    if (correo.trim() === "" || contraseña.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el correo y la contraseña.",
            "error"
        );

        return;
    }


    let usuarioEncontrado = usuarios.find(function(usuario) {

        return usuario.correo === correo.trim() &&
               usuario.contraseña === contraseña.trim();

    });


    if (!usuarioEncontrado) {

        Swal.fire(
            "Error",
            "Correo o contraseña incorrectos.",
            "error"
        );

        return;
    }


    localStorage.setItem(
        "correoUsuario",
        usuarioEncontrado.correo
    );


    localStorage.setItem(
        "rolUsuario",
        usuarioEncontrado.rol
    );


    Swal.fire({

        icon: "success",

        title: "Inicio de sesión correcto",

        text: "Ingresando como " +
              usuarioEncontrado.rol + "...",

        timer: 1500,

        showConfirmButton: false

    });


    setTimeout(function() {

        if (usuarioEncontrado.rol === "docente") {

            window.location.href = "Home_docente.html";

        }


        if (usuarioEncontrado.rol === "estudiante") {

            window.location.href = "Home_estudiante.html";

        }


        if (usuarioEncontrado.rol === "admin") {

            window.location.href = "Home_admin.html";

        }

    }, 1500);

});
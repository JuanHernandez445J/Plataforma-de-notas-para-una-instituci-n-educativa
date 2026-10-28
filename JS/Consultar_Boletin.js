
let formulario = document.getElementById("formularioBoletin");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let documento = document.getElementById("Ndoc").value;
    let periodo = document.getElementById("Ciclo").value;

    if (documento.trim() === "" || periodo.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Campos obligatorios",
            text: "Por favor, completa todos los campos.",
            confirmButtonColor: "#16a34a"
        });
    } else if (
        Number(periodo) < 1 ||
        Number(periodo) > 4 ||
        !Number.isInteger(Number(periodo))
    ) {
        Swal.fire({
            icon: "error",
            title: "Período incorrecto",
            text: "El período debe ser un número entero entre 1 y 4.",
            confirmButtonColor: "#16a34a"
        });
    } else {
        let datos = new FormData();

        datos.append("identificacion", documento.trim());
        datos.append("periodo", periodo);

        fetch("../PHP/Validar_Estudiante.php", {
            method: "POST",
            body: datos
        })
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error en el servidor.");
            }

            return respuesta.json();
        })
        .then(function(resultado) {
            if (resultado.existe) {
                formulario.submit();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "No se puede consultar el boletín",
                    text: resultado.mensaje,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#16a34a"
                });
            }
        })
        .catch(function(error) {
            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "No fue posible verificar el estudiante y sus notas.",
                confirmButtonColor: "#16a34a"
            });
        });
    }
});

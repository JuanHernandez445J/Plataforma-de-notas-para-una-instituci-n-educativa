
let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let grupo = document.getElementById("Grupo").value;
    let periodo = document.getElementById("Ciclo").value;
    let jornada = document.getElementById("Jornad").value;

    if (grupo.trim() === "" || periodo.trim() === "" || jornada.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Campos vacíos",
            text: "Completa todos los campos."
        });
        return;
    }

    if (Number(periodo) < 1 || Number(periodo) > 4 ||
        !Number.isInteger(Number(periodo))) {
        Swal.fire({
            icon: "error",
            title: "Período incorrecto",
            text: "El período debe ser un número entero entre 1 y 4."
        });
        return;
    }

    if (
        jornada.trim().toLowerCase() !== "mañana" &&
        jornada.trim().toLowerCase() !== "manana" &&
        jornada.trim().toLowerCase() !== "tarde" &&
        jornada.trim().toLowerCase() !== "noche"
    ) {
        Swal.fire({
            icon: "error",
            title: "Jornada incorrecta",
            text: "Escribe Mañana, Tarde o Noche."
        });
        return;
    }

    let datos = new FormData();
    datos.append("Grupo", grupo.trim());
    datos.append("Jornad", jornada.trim());

    fetch("../PHP/ValidarGrupo.php", {
        method: "POST",
        body: datos
    })
    .then(respuesta => respuesta.json())
    .then(resultado => {

        if (resultado.error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: resultado.error
            });
            return;
        }

        if (!resultado.existe) {
            Swal.fire({
                icon: "error",
                title: "Grupo no encontrado",
                text: resultado.mensaje
            });
            return;
        }

        Swal.fire({
            icon: "question",
            title: "¿Generar los boletines?",
            text: "El grupo existe con esa jornada. ¿Deseas continuar?",
            showCancelButton: true,
            confirmButtonText: "Sí, generar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#16a34a",
            cancelButtonColor: "#b91c1c"
        }).then(resultadoConfirmacion => {

            if (resultadoConfirmacion.isConfirmed) {
                formulario.submit();
            }

        });

    })
    .catch(error => {
        Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo verificar el grupo. Comprueba que XAMPP esté funcionando."
        });
    });
});

let formulario = document.querySelector("form");


formulario.addEventListener("submit", function(event) {

    event.preventDefault();


    let materia_codigo =
        document.getElementById("materia_codigo").value;

    let materia_nombre =
        document.getElementById("materia_nombre").value;

    let materia_area =
        document.getElementById("materia_area").value;

    let materia_horas =
        document.getElementById("materia_horas").value;

    let materia_descripcion =
        document.getElementById("materia_descripcion").value;


    if (materia_codigo.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese el código de la materia.",
            "error"
        );

        return;
    }


    if (
        materia_nombre.trim() === "" ||
        !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(materia_nombre.trim())
    ) {

        Swal.fire(
            "Error",
            "Ingrese un nombre de materia válido.",
            "error"
        );

        return;
    }


    if (materia_area.trim() === "") {

        Swal.fire(
            "Error",
            "Seleccione el área de la materia.",
            "error"
        );

        return;
    }


    if (
        materia_horas.trim() === "" ||
        isNaN(materia_horas) ||
        materia_horas < 1 ||
        materia_horas > 20
    ) {

        Swal.fire(
            "Error",
            "La intensidad horaria debe estar entre 1 y 20 horas.",
            "error"
        );

        return;
    }


    if (materia_descripcion.trim() === "") {

        Swal.fire(
            "Error",
            "Ingrese una descripción para la materia.",
            "error"
        );

        return;
    }


    Swal.fire(
        "Correcto",
        "Materia creada correctamente.",
        "success"
    );

});
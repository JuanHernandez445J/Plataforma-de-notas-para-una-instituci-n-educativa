let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
event.preventDefault();

let grupo_codigo = document.getElementById("grupo_codigo").value;
let grupo_nombre = document.getElementById("grupo_nombre").value;
let grupo_grado = document.getElementById("grupo_grado").value;
let grupo_jornada = document.getElementById("grupo_jornada").value;
let grupo_cupo = document.getElementById("grupo_cupo").value;
let grupo_anio = document.getElementById("grupo_anio").value;
let grupo_descripcion = document.getElementById("grupo_descripcion").value;

if (grupo_codigo.trim() === "") {
    Swal.fire("Error", "Ingrese el código del grupo.", "error");
    return;
}

if (
    grupo_nombre.trim() === "" ||
    !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9°\s]+$/.test(grupo_nombre.trim())
) {
    Swal.fire("Error", "Ingrese un nombre de grupo válido.", "error");
    return;
}

if (grupo_grado.trim() === "") {
    Swal.fire("Error", "Seleccione el grado del grupo.", "error");
    return;
}

if (grupo_jornada.trim() === "") {
    Swal.fire("Error", "Seleccione la jornada del grupo.", "error");
    return;
}

if (
    grupo_cupo.trim() === "" ||
    isNaN(grupo_cupo) ||
    grupo_cupo < 1 ||
    grupo_cupo > 50
) {
    Swal.fire("Error", "El número de estudiantes debe estar entre 1 y 50.", "error");
    return;
}

if (
    grupo_anio.trim() === "" ||
    isNaN(grupo_anio) ||
    grupo_anio < 2020 ||
    grupo_anio > 2100
) {
    Swal.fire("Error", "Ingrese un año académico válido.", "error");
    return;
}

if (grupo_descripcion.trim() === "") {
    Swal.fire("Error", "Ingrese una descripción para el grupo.", "error");
    return;
}

Swal.fire("Correcto", "Grupo creado correctamente.", "success");

});

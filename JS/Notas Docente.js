let formulario = document.querySelector("form");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let estu_codigo = document.getElementById("estu_codigo").value;
    let nota_evaluacion = document.getElementById("nota_evaluacion").value;
    let nota_valor = document.getElementById("nota_valor").value;
    let nota_porcentaje = document.getElementById("nota_porcentaje").value;

    if (estu_codigo.trim() === "") {
        Swal.fire("Error", "Ingrese el código del estudiante.", "error");
        return;
    }

    if (nota_evaluacion.trim() === "") {
        Swal.fire("Error", "Ingrese la evaluación.", "error");
        return;
    }

    if (nota_valor.trim() === "" || isNaN(nota_valor) || nota_valor < 0 || nota_valor > 5) {
        Swal.fire("Error", "La nota debe estar entre 0 y 5.", "error");
        return;
    }

    if (nota_porcentaje.trim() === "" || isNaN(nota_porcentaje) || nota_porcentaje < 1 || nota_porcentaje > 100) {
        Swal.fire("Error", "El porcentaje debe estar entre 1 y 100.", "error");
        return;
    }

    Swal.fire("Correcto", "Nota añadida correctamente.", "success");
});
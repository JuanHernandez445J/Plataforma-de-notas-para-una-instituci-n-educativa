function mostrarPeriodo(numero) {

    const periodos = document.querySelectorAll(".informacion-periodo");

    periodos.forEach(function(periodo) {
        periodo.style.display = "none";
    });

    const periodoSeleccionado = document.getElementById("periodo" + numero);

    periodoSeleccionado.style.display = "block";
}
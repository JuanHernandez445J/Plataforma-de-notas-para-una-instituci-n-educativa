function mostrarMaterias(materias) {
    let lista = document.getElementById("listaMaterias");

    lista.innerHTML = "";

    materias.forEach(function(materia) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${materia.asig_id}</td>
            <td>${materia.asig_gru_id}</td>
            <td>${materia.asig_nombre}</td>
            <td>${materia.asig_doce_id}</td>
            <td>${materia.asig_logro}</td>
            <td>${materia.asig_intensidad}</td>
        `;

        lista.appendChild(fila);
    });
}

function cargarMaterias() {
    fetch("../PHP/Listar-materias.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar las materias");
            }

            return respuesta.json();
        })
        .then(function(materias) {
            mostrarMaterias(materias);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar las materias.", "error");
        });
}

cargarMaterias();

let buscarMateria = document.getElementById("buscarMateria");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorNombre() {
    let nombre = buscarMateria.value.trim();

    if (nombre === "") {
        Swal.fire("Atención", "Ingrese el nombre de la materia.", "warning");
        return;
    }

    fetch("../PHP/Buscar-materiaNombre.php?nombre=" + encodeURIComponent(nombre))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar materias");
            }

            return respuesta.json();
        })
        .then(function(materias) {
            mostrarMaterias(materias);

            if (materias.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron materias con ese nombre.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + materias.length + " materia(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorNombre);

buscarMateria.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorNombre();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarMateria.value = "";
    cargarMaterias();
});
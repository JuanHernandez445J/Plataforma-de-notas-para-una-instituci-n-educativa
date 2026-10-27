function mostrarGrupos(grupos) {
    let lista = document.getElementById("listaGrupos");

    lista.innerHTML = "";

    grupos.forEach(function(grupo) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${grupo.gru_id}</td>
            <td>${grupo.gru_jornada}</td>
            <td>${grupo.gru_nombre}</td>
        `;

        lista.appendChild(fila);
    });
}

function cargarGrupos() {
    fetch("../PHP/Listar-grupos.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar los grupos");
            }

            return respuesta.json();
        })
        .then(function(grupos) {
            mostrarGrupos(grupos);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar los grupos.", "error");
        });
}

cargarGrupos();

let buscarGrupo = document.getElementById("buscarGrupo");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorNombre() {
    let nombre = buscarGrupo.value.trim();

    if (nombre === "") {
        Swal.fire("Atención", "Ingrese el nombre del grupo.", "warning");
        return;
    }

    fetch("../PHP/Buscar-grupoNombre.php?nombre=" + encodeURIComponent(nombre))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar grupos");
            }

            return respuesta.json();
        })
        .then(function(grupos) {
            mostrarGrupos(grupos);

            if (grupos.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron grupos con ese nombre.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + grupos.length + " grupo(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorNombre);

buscarGrupo.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorNombre();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarGrupo.value = "";
    cargarGrupos();
});
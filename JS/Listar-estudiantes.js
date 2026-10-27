function mostrarestudiantes(estudiantes) {
    let lista = document.getElementById("listaEstudiantes");

    lista.innerHTML = "";

    estudiantes.forEach(function(estudiante) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${estudiante.estu_id}</td>
            <td><img src="${estudiante.estu_foto_url}"
                width="60"
                height="60"
                alt="Foto del estudiante"></td>
            <td>${estudiante.estu_nombre}</td>
            <td>${estudiante.estu_apellido}</td>
            <td>${estudiante.estu_identificacion}</td>
            <td>${estudiante.estu_gru_id}</td>
            <td>${estudiante.estu_acud_id}</td>
        `;
        lista.appendChild(fila);
    });
}

function cargarEstudiantes() {
    fetch("../PHP/Listar-estudiantes.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar los estudiantes");
            }

            return respuesta.json();
        })
        .then(function(estudiantes) {
            mostrarestudiantes(estudiantes);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar los estudiantes.", "error");
        });
}

cargarEstudiantes();

let buscarEstudiante = document.getElementById("buscarEstudiante");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorApellido() {
    let apellido = buscarEstudiante.value.trim();

    if (apellido === "") {
        Swal.fire("Atención", "Ingrese el apellido del estudiante.", "warning");
        return;
    }

    fetch("../PHP/Buscar-estudianteApellido.php?apellido=" + encodeURIComponent(apellido))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar estudiantes");
            }

            return respuesta.json();
        })
        .then(function(estudiantes) {
            mostrarestudiantes(estudiantes);

            if (estudiantes.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron estudiantes con ese apellido.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + estudiantes.length + " estudiante(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorApellido);

buscarEstudiante.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorApellido();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarEstudiante.value = "";
    cargarEstudiantes();
});
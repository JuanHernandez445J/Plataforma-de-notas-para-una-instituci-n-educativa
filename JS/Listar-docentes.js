function mostrarDocentes(docentes) {
    let lista = document.getElementById("listaDocentes");

    lista.innerHTML = "";

    docentes.forEach(function(docente) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${docente.doce_id}</td>
            <td><img src="${docente.doce_foto_url}"
                width="60"
                height="60"
                alt="Foto del docente"></td>
            <td>${docente.doce_nombre}</td>
            <td>${docente.doce_apellido}</td>
            <td>${docente.doce_identificacion}</td>
            <td>${docente.doce_direccion}</td>
            <td>${docente.doce_telefono}</td>
            <td>${docente.doce_email}</td>
            <td>${docente.doce_especialidad}</td>
        `;

        lista.appendChild(fila);
    });
}

function cargarDocentes() {
    fetch("../PHP/Listar-docentes.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar los docentes");
            }

            return respuesta.json();
        })
        .then(function(docentes) {
            mostrarDocentes(docentes);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar los docentes.", "error");
        });
}

cargarDocentes();

let buscarDocente = document.getElementById("buscarDocente");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorApellido() {
    let apellido = buscarDocente.value.trim();

    if (apellido === "") {
        Swal.fire("Atención", "Ingrese el apellido del docente.", "warning");
        return;
    }

    fetch("../PHP/Buscar-docenteApellido.php?apellido=" + encodeURIComponent(apellido))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar docentes");
            }

            return respuesta.json();
        })
        .then(function(docentes) {
            mostrarDocentes(docentes);

            if (docentes.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron docentes con ese apellido.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + docentes.length + " docente(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorApellido);

buscarDocente.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorApellido();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarDocente.value = "";
    cargarDocentes();
});
function mostrarAcudientes(acudientes) {
    let lista = document.getElementById("listaAcudientes");

    lista.innerHTML = "";

    acudientes.forEach(function(acudiente) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${acudiente.acud_id}</td>
            <td>${acudiente.acud_parentesco}</td>
            <td>${acudiente.acud_nombre}</td>
            <td>${acudiente.acud_apellido}</td>
            <td>${acudiente.acud_identificacion}</td>
            <td>${acudiente.acud_direccion}</td>
            <td>${acudiente.acud_telefono}</td>
            <td>${acudiente.acud_email}</td>
        `;

        lista.appendChild(fila);
    });
}

function cargarAcudientes() {
    fetch("../PHP/Listar-acudientes.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar los acudientes");
            }

            return respuesta.json();
        })
        .then(function(acudientes) {
            mostrarAcudientes(acudientes);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar los acudientes.", "error");
        });
}

cargarAcudientes();

let buscarAcudiente = document.getElementById("buscarAcudiente");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorApellido() {
    let apellido = buscarAcudiente.value.trim();

    if (apellido === "") {
        Swal.fire("Atención", "Ingrese el apellido del acudiente.", "warning");
        return;
    }

    fetch("../PHP/Buscar-acudienteApellido.php?apellido=" + encodeURIComponent(apellido))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar acudientes");
            }

            return respuesta.json();
        })
        .then(function(acudientes) {
            mostrarAcudientes(acudientes);

            if (acudientes.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron acudientes con ese apellido.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + acudientes.length + " acudiente(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorApellido);

buscarAcudiente.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorApellido();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarAcudiente.value = "";
    cargarAcudientes();
});
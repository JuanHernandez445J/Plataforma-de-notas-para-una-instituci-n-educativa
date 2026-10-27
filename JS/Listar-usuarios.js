function mostrarUsuarios(usuarios) {
    let lista = document.getElementById("listaUsuarios");

    lista.innerHTML = "";

    usuarios.forEach(function(usuario) {
        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${usuario.usua_id}</td>
            <td>${usuario.usua_activo}</td>
            <td>${usuario.usua_usuario}</td>
            <td>${usuario.usua_rol}</td>
        `;

        lista.appendChild(fila);
    });
}

function cargarUsuarios() {
    fetch("../PHP/Listar-usuarios.php")
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al consultar los usuarios");
            }

            return respuesta.json();
        })
        .then(function(usuarios) {
            mostrarUsuarios(usuarios);
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudieron cargar los usuarios.", "error");
        });
}

cargarUsuarios();

let buscarUsuario = document.getElementById("buscarUsuario");
let buscarBtn = document.getElementById("buscarBtn");
let mostrarTodos = document.getElementById("mostrarTodos");

function buscarPorNombre() {
    let nombre = buscarUsuario.value.trim();

    if (nombre === "") {
        Swal.fire("Atención", "Ingrese el nombre del usuario.", "warning");
        return;
    }

    fetch("../PHP/Buscar-usuarioNombre.php?nombre=" + encodeURIComponent(nombre))
        .then(function(respuesta) {
            if (!respuesta.ok) {
                throw new Error("Error al buscar usuarios");
            }

            return respuesta.json();
        })
        .then(function(usuarios) {
            mostrarUsuarios(usuarios);

            if (usuarios.length === 0) {
                Swal.fire("Sin resultados", "No se encontraron usuarios con ese nombre.", "info");
            } else {
                Swal.fire("Correcto", "Se encontraron " + usuarios.length + " usuario(s).", "success");
            }
        })
        .catch(function(error) {
            console.log("Error:", error);
            Swal.fire("Error", "No se pudo realizar la búsqueda.", "error");
        });
}

buscarBtn.addEventListener("click", buscarPorNombre);

buscarUsuario.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        buscarPorNombre();
    }
});

mostrarTodos.addEventListener("click", function() {
    buscarUsuario.value = "";
    cargarUsuarios();
});
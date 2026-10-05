fetch("../PHP/Listar-usuarios.php")

    .then(function(respuesta) {

        return respuesta.json();

    })

    .then(function(usuarios) {

        let lista = document.getElementById("listaUsuarios");


        usuarios.forEach(function(usuario) {

            let fila = document.createElement("tr");


            let estado = "";

            if (usuario.usua_activo == 1) {

                estado = "Activo";

            } else {

                estado = "Inactivo";

            }


            fila.innerHTML = `

                <td>${usuario.usua_id}</td>

                <td>${usuario.usua_usuario}</td>

                <td>${usuario.usua_rol}</td>

                <td>${estado}</td>

            `;


            lista.appendChild(fila);

        });

    })

    .catch(function(error) {

        console.log("Error:", error);

    });
fetch("../PHP/Listar-docentes.php")

    .then(function(respuesta) {

        return respuesta.json();

    })

    .then(function(docentes) {

        let lista = document.getElementById("listaDocentes");


        docentes.forEach(function(docente) {

            let fila = document.createElement("tr");


            fila.innerHTML = `

                <td>${docente.doce_id}</td>

                <td>${docente.doce_foto}</td>

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

    })

    .catch(function(error) {

        console.log("Error:", error);

    });
fetch("../PHP/Listar-estudiantes.php")
    .then(function(respuesta) {
        return respuesta.json();
    })
    .then(function(estudiantes) {

        let lista = document.getElementById("listaEstudiantes");

        estudiantes.forEach(function(estudiante) {

            let fila = document.createElement("tr");

            fila.innerHTML = `
                <td>${estudiante.estu_id}</td>
                <td>${estudiante.estu_foto}</td>
                <td>${estudiante.estu_nombre}</td>
                <td>${estudiante.estu_apellido}</td>
                <td>${estudiante.estu_identificacion}</td>
                <td>${estudiante.estu_gru_id}</td>
            `;

            lista.appendChild(fila);
        });
    });
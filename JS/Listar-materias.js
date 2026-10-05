fetch("../PHP/Listar-materias.php")

    .then(function(respuesta) {

        return respuesta.json();

    })

    .then(function(materias) {

        let lista = document.getElementById("listaMaterias");

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

    })

    .catch(function(error) {

        console.log("Error:", error);

    });
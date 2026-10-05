fetch("../PHP/Listar-grupos.php")

    .then(function(respuesta) {

        return respuesta.json();

    })

    .then(function(grupos) {

        let lista = document.getElementById("listaGrupos");


        grupos.forEach(function(grupo) {

            let fila = document.createElement("tr");


            fila.innerHTML = `
                <td>${grupo.gru_id}</td>
                <td>${grupo.gru_jornada}</td>
                <td>${grupo.gru_nombre}</td>
            `;


            lista.appendChild(fila);

        });

    })

    .catch(function(error) {

        console.log("Error:", error);

    });
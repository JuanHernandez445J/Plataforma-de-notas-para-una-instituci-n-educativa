fetch("../PHP/Listar-acudientes.php")
    .then(function(respuesta) {
        return respuesta.json();
    })
    .then(function(acudientes) {

        let lista = document.getElementById("listaAcudientes");

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

    })
    .catch(function(error) {
        console.log("Error:", error);
    });
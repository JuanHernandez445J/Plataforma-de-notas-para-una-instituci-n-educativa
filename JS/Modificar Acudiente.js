
let formulario = document.getElementById("formularioAcudiente");
let botonBuscar = document.getElementById("buscar");
let camposAcudiente = document.getElementById("camposAcudiente");
let campoId = document.getElementById("acud_id");

camposAcudiente.style.display = "none";

botonBuscar.addEventListener("click", function() {
    let acud_id = campoId.value.trim();

    camposAcudiente.style.display = "none";

    if (acud_id === "") {
        Swal.fire("Error", "Ingrese el ID del acudiente.", "error");
        return;
    }

    fetch("../PHP/Buscar-acudiente.php?id=" + encodeURIComponent(acud_id))
    .then(function(respuesta) {
        if (!respuesta.ok) {
            throw new Error("Error del servidor");
        }
        return respuesta.json();
    })
    .then(function(acudiente) {
        
if (acudiente.exito) {
    document.getElementById("acud_parentesco").value = acudiente.acud_parentesco;
    document.getElementById("acud_nombre").value = acudiente.acud_nombre;
    document.getElementById("acud_apellido").value = acudiente.acud_apellido;
    document.getElementById("acud_identificacion").value = acudiente.acud_identificacion;
    document.getElementById("acud_direccion").value = acudiente.acud_direccion;
    document.getElementById("acud_telefono").value = acudiente.acud_telefono;
    document.getElementById("acud_email").value = acudiente.acud_email;

    camposAcudiente.style.display = "block";
} else {
    formulario.reset();
    camposAcudiente.style.display = "none";

    Swal.fire("Error", "No se encontró el acudiente.", "error");
}
    
    })
    .catch(function() {
        Swal.fire("Error", "No se pudo consultar el acudiente.", "error");
    });
});

campoId.addEventListener("input", function() {
    camposAcudiente.style.display = "none";
});

formulario.addEventListener("submit", function(event) {
    event.preventDefault();

    let acud_id = campoId.value.trim();
    let acud_parentesco = document.getElementById("acud_parentesco").value;
    let acud_nombre = document.getElementById("acud_nombre").value;
    let acud_apellido = document.getElementById("acud_apellido").value;
    let acud_identificacion = document.getElementById("acud_identificacion").value;
    let acud_direccion = document.getElementById("acud_direccion").value;
    let acud_telefono = document.getElementById("acud_telefono").value;
    let acud_email = document.getElementById("acud_email").value;

    if (acud_id === "" || camposAcudiente.style.display === "none") {
        Swal.fire("Error", "Primero busca un acudiente existente.", "error");
        return;
    }

    if (acud_parentesco.trim() === "") {
        Swal.fire("Error", "Ingrese el parentesco.", "error");
        return;
    }

    if (acud_nombre.trim() === "" || acud_apellido.trim() === "") {
        Swal.fire("Error", "Ingrese el nombre y el apellido.", "error");
        return;
    }

    if (acud_identificacion.trim() === "" || isNaN(acud_identificacion)) {
        Swal.fire("Error", "Ingrese una identificación válida.", "error");
        return;
    }

    if (acud_direccion.trim() === "") {
        Swal.fire("Error", "Ingrese la dirección.", "error");
        return;
    }

    if (acud_telefono.trim() === "" || isNaN(acud_telefono)) {
        Swal.fire("Error", "Ingrese un teléfono válido.", "error");
        return;
    }

    if (acud_email.trim() === "" || !acud_email.includes("@")) {
        Swal.fire("Error", "Ingrese un correo válido.", "error");
        return;
    }

    Swal.fire({
        title: "¿Guardar cambios?",
        text: "Se actualizará la información del acudiente.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, guardar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#16a34a"
    }).then(function(resultado) {
        if (resultado.isConfirmed) {
            let datos = new FormData();

            datos.append("acud_id", acud_id);
            datos.append("acud_parentesco", acud_parentesco.trim());
            datos.append("acud_nombre", acud_nombre.trim());
            datos.append("acud_apellido", acud_apellido.trim());
            datos.append("acud_identificacion", acud_identificacion.trim());
            datos.append("acud_direccion", acud_direccion.trim());
            datos.append("acud_telefono", acud_telefono.trim());
            datos.append("acud_email", acud_email.trim());

            fetch("../PHP/Modificar-acudiente.php", {
                method: "POST",
                body: datos
            })
            .then(function(respuesta) {
                if (!respuesta.ok) {
                    throw new Error("Error del servidor");
                }
                return respuesta.json();
            })
            .then(function(resultado) {
                if (resultado.exito) {
                    Swal.fire("Correcto", "Acudiente modificado correctamente.", "success");
                } else {
                    Swal.fire("Error", resultado.mensaje, "error");
                }
            })
            .catch(function() {
                Swal.fire("Error", "No se pudieron guardar los cambios.", "error");
            });
        }
    });
});

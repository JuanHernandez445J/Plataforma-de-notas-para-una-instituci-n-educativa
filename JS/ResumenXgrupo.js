let formulario = document.getElementById('formularioResumen');
let grupo = document.getElementById('Grupo');
let jornada = document.getElementById('Jorna');

formulario.addEventListener('submit', function(event) {
    event.preventDefault();

    if (grupo.value.trim() === "" || jornada.value.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Debes ingresar el grupo y la jornada.'
        });
        return;
    }

    let nombreGrupo = grupo.value.trim();
    let nombreJornada = jornada.value.trim().toUpperCase();

    if (nombreJornada === "MANANA") {
        nombreJornada = "MAÑANA";
    }

    if (nombreJornada !== "MAÑANA" && nombreJornada !== "TARDE" && nombreJornada !== "NOCHE") {
        Swal.fire({
            icon: 'error',
            title: 'Jornada incorrecta',
            text: 'Escribe MAÑANA, TARDE o NOCHE.'
        });
        return;
    }

    Swal.fire({
        title: 'Verificando datos',
        text: 'Estamos comprobando el grupo y la jornada.',
        allowOutsideClick: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });

    let datos = new FormData();

    datos.append('Grupo', nombreGrupo);
    datos.append('Jorna', nombreJornada);

    fetch('../PHP/ValidarGrupoResumen.php', {
        method: 'POST',
        body: datos
    })
    .then(function(respuesta) {
        if (!respuesta.ok) {
            throw new Error('Error en la respuesta del servidor.');
        }

        return respuesta.json();
    })
    .then(function(resultado) {
        if (resultado.existe) {
            Swal.fire({
                icon: 'success',
                title: 'Grupo encontrado',
                text: 'El grupo existe y corresponde a la jornada indicada.',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#16a34a'
            }).then(function() {
                grupo.value = nombreGrupo;
                jornada.value = nombreJornada;
                formulario.submit();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Grupo no encontrado',
                text: 'No existe ese grupo con la jornada ingresada.',
                confirmButtonColor: '#b91c1c'
            });
        }
    })
    .catch(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No fue posible verificar los datos. Inténtalo nuevamente.'
        });
    });
});
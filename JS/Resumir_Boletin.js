
let formulario = document.getElementById('formularioResumen');
let identificacion = document.getElementById('Ndoc');

formulario.addEventListener('submit', function(event) {
    event.preventDefault();

    if (identificacion.value.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Campo vacío',
            text: 'Debes ingresar el documento de identidad.'
        });
        return;
    }

    let datos = new FormData();
    datos.append('Ndoc', identificacion.value.trim());

    Swal.fire({
        title: 'Verificando identificación',
        text: 'Consultando los datos del estudiante...',
        allowOutsideClick: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });

    fetch('../PHP/ValidarIdentificacion.php', {
        method: 'POST',
        body: datos
    })
    .then(function(respuesta) {
        if (!respuesta.ok) {
            throw new Error('Error del servidor');
        }
        return respuesta.json();
    })
    .then(function(resultado) {
        if (resultado.existe) {
            Swal.fire({
                icon: 'success',
                title: 'Identificación encontrada',
                text: 'El estudiante existe en la base de datos.',
                confirmButtonColor: '#16a34a'
            }).then(function() {
                formulario.submit();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Identificación no encontrada',
                text: 'No existe un estudiante con ese documento.'
            });
        }
    })
    .catch(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo verificar la identificación. Revisa la conexión con el servidor.'
        });
    });
});

let formulario = document.getElementById('formularioEscolar');
let verificandoNotas = false;

formulario.addEventListener('submit', function(event) {
    if (verificandoNotas) {
        return;
    }

    event.preventDefault();

    let datos = new FormData(formulario);

    fetch('VerificarNotas.php', {
        method: 'POST',
        body: datos
    })
    .then(function(respuesta) {
        return respuesta.text();
    })
    .then(function(texto) {
        let resultado;

        try {
            resultado = JSON.parse(texto);
        } catch (error) {
            throw new Error('VerificarNotas.php no devolvió JSON. Revisa si hay errores PHP.');
        }

        if (resultado.error) {
            throw new Error(resultado.mensaje);
        }

        if (resultado.existenNotas) {
            Swal.fire({
                title: 'Notas ya registradas',
                text: 'Ya existen notas para este grupo, materia y período.',
                icon: 'warning',
                confirmButtonText: 'Volver',
                confirmButtonColor: '#16a34a',
                allowOutsideClick: false
            });

            return;
        }

        verificandoNotas = true;
        formulario.submit();
    })
    .catch(function(error) {
        Swal.fire({
            title: 'Error al verificar',
            text: error.message,
            icon: 'error',
            confirmButtonColor: '#b91c1c'
        });
    });
});
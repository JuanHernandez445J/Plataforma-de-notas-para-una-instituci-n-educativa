
let formulario = document.getElementById('formularioEliminar');
let selectJornada = document.getElementById('Jornad');
let selectGrupo = document.getElementById('Grupo');
let selectMateria = document.getElementById('Materia');

selectJornada.addEventListener('change', function() {
    selectGrupo.innerHTML = '<option value="">Seleccione un grupo</option>';
    selectMateria.innerHTML = '<option value="">Seleccione primero el grupo</option>';
    selectMateria.disabled = true;

    let jornada = this.value.toLowerCase();

    let gruposFiltrados = matrizGrupos.filter(function(grupo) {
        return grupo.gru_jornada && grupo.gru_jornada.toLowerCase() === jornada;
    });

    gruposFiltrados.forEach(function(grupo) {
        let opcion = document.createElement('option');
        opcion.value = grupo.gru_id;
        opcion.textContent = grupo.gru_nombre;
        selectGrupo.appendChild(opcion);
    });

    selectGrupo.disabled = gruposFiltrados.length === 0;
});

selectGrupo.addEventListener('change', function() {
    selectMateria.innerHTML = '<option value="">Seleccione una materia</option>';

    let materiasFiltradas = matrizMaterias.filter(function(materia) {
        return String(materia.asig_gru_id) === String(this.value);
    }, this);

    materiasFiltradas.forEach(function(materia) {
        let opcion = document.createElement('option');
        opcion.value = materia.asig_id;
        opcion.textContent = materia.asig_nombre;
        selectMateria.appendChild(opcion);
    });

    selectMateria.disabled = materiasFiltradas.length === 0;
});

formulario.addEventListener('submit', function(event) {
    event.preventDefault();

    Swal.fire({
        title: '¿Consultar las notas?',
        text: 'Se buscarán las notas de la materia, el grupo y el período seleccionados.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#b91c1c'
    }).then(function(confirmacion) {
        if (!confirmacion.isConfirmed) {
            return;
        }

        let datos = new FormData(formulario);
        datos.append('accion', 'contar');

        fetch('ProcesarEliminarNotas.php', {
            method: 'POST',
            body: datos
        })
        .then(function(respuesta) {
            return respuesta.json();
        })
        .then(function(resultado) {
            if (resultado.error) {
                throw new Error(resultado.mensaje);
            }

            if (resultado.total === 0) {
                Swal.fire({
                    title: 'Sin notas',
                    text: 'No existen notas para la selección realizada.',
                    icon: 'info',
                    confirmButtonColor: '#16a34a'
                });
                return;
            }

            Swal.fire({
                title: '¿Eliminar las notas?',
                text: 'Se eliminarán ' + resultado.total + ' registros de esta materia, grupo y período. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#16a34a'
            }).then(function(confirmacionEliminar) {
                if (!confirmacionEliminar.isConfirmed) {
                    return;
                }

                let datosEliminar = new FormData(formulario);
                datosEliminar.append('accion', 'eliminar');

                fetch('ProcesarEliminarNotas.php', {
                    method: 'POST',
                    body: datosEliminar
                })
                .then(function(respuesta) {
                    return respuesta.json();
                })
                .then(function(resultadoEliminar) {
                    if (resultadoEliminar.error) {
                        throw new Error(resultadoEliminar.mensaje);
                    }

                    Swal.fire({
                        title: 'Proceso terminado',
                        text: resultadoEliminar.mensaje,
                        icon: 'success',
                        confirmButtonColor: '#16a34a'
                    });
                })
                .catch(function(error) {
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error',
                        confirmButtonColor: '#b91c1c'
                    });
                });
            });
        })
        .catch(function(error) {
            Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#b91c1c'
            });
        });
    });
});

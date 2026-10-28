
let formulario = document.getElementById('formularioNotas');
let selectJornada = document.getElementById('Jornad');
let selectGrupo = document.getElementById('Grupo');
let selectMateria = document.getElementById('Materia');
let selectPeriodo = document.getElementById('Periodo');
let buscarEstudiante = document.getElementById('buscarEstudiante');
let tablaNotas = document.getElementById('tablaNotas');
let seccionResultados = document.getElementById('seccionResultados');
let totalResultados = document.getElementById('totalResultados');
let botonLimpiar = document.getElementById('limpiar');

let notasCargadas = [];

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

    let grupoId = this.value;

    let materiasFiltradas = matrizMaterias.filter(function(materia) {
        return String(materia.asig_gru_id) === String(grupoId);
    });

    materiasFiltradas.forEach(function(materia) {
        let opcion = document.createElement('option');
        opcion.value = materia.asig_id;
        opcion.textContent = materia.asig_nombre;
        selectMateria.appendChild(opcion);
    });

    selectMateria.disabled = materiasFiltradas.length === 0;
});

function crearCelda(fila, valor) {
    let celda = document.createElement('td');
    celda.textContent = valor === null || valor === undefined || valor === '' ? '-' : valor;
    fila.appendChild(celda);
}

function mostrarNotas() {
    let texto = buscarEstudiante.value.trim().toLowerCase();

    let notasFiltradas = notasCargadas.filter(function(nota) {
        let nombre = ((nota.estu_nombre || '') + ' ' + (nota.estu_apellido || '')).toLowerCase();
        let identificacion = String(nota.estu_identificacion || '').toLowerCase();

        return nombre.includes(texto) || identificacion.includes(texto);
    });

    tablaNotas.replaceChildren();

    notasFiltradas.forEach(function(nota) {
        let fila = document.createElement('tr');

        crearCelda(fila, nota.nota_id);
        crearCelda(fila, nota.estu_identificacion);
        crearCelda(fila, (nota.estu_nombre || '') + ' ' + (nota.estu_apellido || ''));
        crearCelda(fila, nota.nota_id === null ? 'Sin nota' : nota.nota_nota);
        crearCelda(fila, nota.nota_id === null ? '-' : nota.nota_inasistencias);
        crearCelda(fila, nota.nota_id === null ? '-' : nota.nota_fechacorte);
        crearCelda(fila, nota.nota_id === null ? '-' : nota.nota_observacion);

        tablaNotas.appendChild(fila);
    });

    totalResultados.textContent = 'Estudiantes encontrados: ' + notasFiltradas.length;
}

formulario.addEventListener('submit', function(event) {
    event.preventDefault();

    let datos = new FormData(formulario);

    fetch('ConsultarNotas.php', {
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

        notasCargadas = resultado.notas;
        buscarEstudiante.value = '';
        seccionResultados.hidden = false;
        mostrarNotas();

        if (notasCargadas.length === 0) {
            Swal.fire({
                title: 'Sin estudiantes',
                text: 'No hay estudiantes registrados en el grupo seleccionado.',
                icon: 'info',
                confirmButtonColor: '#16a34a'
            });
        }
    })
    .catch(function(error) {
        Swal.fire({
            title: 'Error',
            text: error.message || 'No se pudo consultar el listado de notas.',
            icon: 'error',
            confirmButtonColor: '#b91c1c'
        });
    });
});

buscarEstudiante.addEventListener('input', mostrarNotas);

botonLimpiar.addEventListener('click', function() {
    notasCargadas = [];
    tablaNotas.replaceChildren();
    buscarEstudiante.value = '';
    seccionResultados.hidden = true;

    selectGrupo.innerHTML = '<option value="">Seleccione primero la jornada</option>';
    selectGrupo.disabled = true;

    selectMateria.innerHTML = '<option value="">Seleccione primero el grupo</option>';
    selectMateria.disabled = true;
});

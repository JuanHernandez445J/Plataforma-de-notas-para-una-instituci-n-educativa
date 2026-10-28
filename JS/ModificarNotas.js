
let formulario = document.getElementById('formularioNotas');
let jornada = document.getElementById('Jornada');
let grupo = document.getElementById('Grupo');
let materia = document.getElementById('Materia');
let periodo = document.getElementById('Periodo');
let seccionResultados = document.getElementById('seccionResultados');
let tablaNotas = document.getElementById('tablaNotas');
let buscarEstudiante = document.getElementById('buscarEstudiante');
let totalResultados = document.getElementById('totalResultados');
let notasActuales = [];


function cargarGrupos() {
    grupo.innerHTML = '<option value="">Seleccione un grupo</option>';

    let jornadaSeleccionada = jornada.value.trim().toLowerCase();

    let gruposFiltrados = matrizGrupos.filter(function(item) {
        return String(item.gru_jornada).trim().toLowerCase() === jornadaSeleccionada;
    });

    gruposFiltrados.forEach(function(item) {
        let opcion = document.createElement('option');
        opcion.value = item.gru_id;
        opcion.textContent = item.gru_nombre;
        grupo.appendChild(opcion);
    });

    grupo.disabled = false;
    materia.innerHTML = '<option value="">Seleccione primero el grupo</option>';
    materia.disabled = true;
}


function cargarMaterias() {
    materia.innerHTML = '<option value="">Seleccione una materia</option>';

    let materiasFiltradas = matrizMaterias.filter(function(item) {
        return String(item.asig_gru_id) === grupo.value;
    });

    materiasFiltradas.forEach(function(item) {
        materia.innerHTML += `<option value="${item.asig_id}">${item.asig_nombre}</option>`;
    });

    materia.disabled = false;
}

function escaparHTML(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, function(caracter) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[caracter];
    });
}

jornada.addEventListener('change', cargarGrupos);
grupo.addEventListener('change', cargarMaterias);

formulario.addEventListener('submit', async function(evento) {
    evento.preventDefault();

    let datos = new URLSearchParams();
    datos.append('grupo', grupo.value);
    datos.append('materia', materia.value);
    datos.append('periodo', periodo.value);

    try {
        let respuesta = await fetch('ConsultarNotasModificar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: datos
        });

        let resultado = await respuesta.json();

        if (!respuesta.ok || resultado.error) {
            throw new Error(resultado.error || 'No se pudieron consultar las notas.');
        }

        notasActuales = resultado;
        seccionResultados.hidden = false;
        buscarEstudiante.value = '';
        mostrarNotas(notasActuales);

        if (notasActuales.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Sin notas registradas',
                text: 'No existen notas para el grupo, la materia y el período seleccionados.'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No fue posible consultar las notas.'
        });
    }
});

function mostrarNotas(notas) {
    tablaNotas.innerHTML = '';

    totalResultados.textContent = 'Notas encontradas: ' + notas.length;

    notas.forEach(function(nota) {
        let fila = document.createElement('tr');

        fila.innerHTML = `
            <td>${escaparHTML(nota.nota_id)}</td>
            <td>${escaparHTML(nota.estu_identificacion)}</td>
            <td>${escaparHTML(nota.estu_nombre)} ${escaparHTML(nota.estu_apellido)}</td>
            <td>
                <input type="number" class="calificacion" min="0" step="any"
                    value="${escaparHTML(nota.nota_nota)}" required>
            </td>
            <td>
                <input type="number" class="inasistencias" min="0" step="1"
                    value="${escaparHTML(nota.nota_inasistencias ?? 0)}" required>
            </td>
            <td>${escaparHTML(nota.nota_fechacorte || 'Sin fecha')}</td>
            <td>
                <input type="text" class="observacion"
                    value="${escaparHTML(nota.nota_observacion || '')}">
            </td>
            <td>
                <button type="button" class="guardar">Guardar</button>
            </td>
        `;

        fila.querySelector('.guardar').addEventListener('click', function() {
            guardarNota(nota.nota_id, fila);
        });

        tablaNotas.appendChild(fila);
    });
}

async function guardarNota(notaId, fila) {
    let calificacion = fila.querySelector('.calificacion').value.trim();
    let inasistencias = fila.querySelector('.inasistencias').value.trim();
    let observacion = fila.querySelector('.observacion').value.trim();

    if (calificacion === '' || !Number.isFinite(Number(calificacion)) || Number(calificacion) < 0) {
        Swal.fire('Dato inválido', 'Ingrese una calificación numérica válida.', 'warning');
        return;
    }

    if (inasistencias === '' || !Number.isInteger(Number(inasistencias)) || Number(inasistencias) < 0) {
        Swal.fire('Dato inválido', 'Las inasistencias deben ser un número entero igual o mayor que cero.', 'warning');
        return;
    }

    let confirmacion = await Swal.fire({
        title: '¿Modificar esta nota?',
        text: 'Se guardarán los nuevos datos del estudiante.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a'
    });

    if (!confirmacion.isConfirmed) {
        return;
    }

    let datos = new URLSearchParams();
    datos.append('nota_id', notaId);
    datos.append('nota_nota', calificacion);
    datos.append('nota_inasistencias', inasistencias);
    datos.append('nota_observacion', observacion);

    try {
        let respuesta = await fetch('GuardarNotasModificadas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: datos
        });

        let resultado = await respuesta.json();

        if (!respuesta.ok || resultado.error) {
            throw new Error(resultado.error || 'No se pudo guardar la nota.');
        }

        Swal.fire({
            icon: 'success',
            title: 'Nota modificada',
            text: resultado.mensaje,
            confirmButtonColor: '#16a34a'
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No fue posible guardar los cambios.'
        });
    }
}

buscarEstudiante.addEventListener('input', function() {
    let texto = buscarEstudiante.value.trim().toLowerCase();

    let filtradas = notasActuales.filter(function(nota) {
        let nombre = (nota.estu_nombre + ' ' + nota.estu_apellido).toLowerCase();
        let identificacion = String(nota.estu_identificacion).toLowerCase();

        return nombre.includes(texto) || identificacion.includes(texto);
    });

    mostrarNotas(filtradas);
});

formulario.addEventListener('reset', function() {
    setTimeout(function() {
        grupo.innerHTML = '<option value="">Seleccione primero la jornada</option>';
        grupo.disabled = true;
        materia.innerHTML = '<option value="">Seleccione primero el grupo</option>';
        materia.disabled = true;
        seccionResultados.hidden = true;
        buscarEstudiante.value = '';
        tablaNotas.innerHTML = '';
        notasActuales = [];
        totalResultados.textContent = '';
    }, 0);
});

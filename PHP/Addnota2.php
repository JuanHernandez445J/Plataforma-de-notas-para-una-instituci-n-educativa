
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('conexion.php');

$listaAlumnosPHP = [];
$Docente = '';
$Bgrupo = '';
$Bjornada = '';
$Bperiodo = '';
$BFecha = '';
$row = [];
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $B_jornada = $_POST['Jornad'] ?? '';
    $B_grup = $_POST['Grupo'] ?? '';
    $B_materia = $_POST['Materia'] ?? '';
    $B_periodo = $_POST['Periodo'] ?? '';
    $BFecha = $_POST['Fcorte'] ?? '';

    $Bjornada = htmlspecialchars($B_jornada, ENT_QUOTES, 'UTF-8');
    $Bgrupo = htmlspecialchars($B_grup, ENT_QUOTES, 'UTF-8');
    $Bperiodo = htmlspecialchars($B_periodo, ENT_QUOTES, 'UTF-8');
    $BFecha = htmlspecialchars($BFecha, ENT_QUOTES, 'UTF-8');

    if ($B_jornada != '' && $B_grup != '' && $B_materia != '' && $B_periodo != '') {

        $consulta = "
            SELECT
                asignaturas.asig_id,
                asignaturas.asig_nombre,
                asignaturas.asig_logro1,
                asignaturas.asig_logro2,
                asignaturas.asig_logro3,
                asignaturas.asig_logro4,
                grupos.gru_id,
                grupos.gru_jornada,
                grupos.gru_nombre,
                estudiantes.estu_id,
                estudiantes.estu_apellido,
                estudiantes.estu_nombre,
                docentes.doce_apellido,
                docentes.doce_nombre
            FROM estudiantes
            INNER JOIN grupos
                ON grupos.gru_id = estudiantes.estu_gru_id
            INNER JOIN asignaturas
                ON grupos.gru_id = asignaturas.asig_gru_id
            INNER JOIN docentes
                ON docentes.doce_id = asignaturas.asig_doce_id
            WHERE grupos.gru_jornada = ?
              AND grupos.gru_nombre = ?
              AND asignaturas.asig_id = ?
            ORDER BY estudiantes.estu_apellido, estudiantes.estu_nombre
        ";

        $stmt = $conexion->prepare($consulta);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        $materiaId = (int)$B_materia;

        $stmt->bind_param(
            "ssi",
            $B_jornada,
            $B_grup,
            $materiaId
        );

        if (!$stmt->execute()) {
            die("Error al consultar estudiantes: " . $stmt->error);
        }

        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $listaAlumnosPHP[] = $fila;
        }

        $stmt->close();

        if (count($listaAlumnosPHP) > 0) {
            $row = $listaAlumnosPHP[0];
            $Docente = $row['doce_apellido'] . ' ' . $row['doce_nombre'];
        } else {
            $mensaje = "No existen estudiantes para esa jornada, grupo y asignatura.";
        }

    } else {
        $mensaje = "Faltan datos para consultar los estudiantes.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Notas</title>

    <style>
        
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 30px;
    font-family: Arial, sans-serif;
    background-color: #ffffff;
    color: #b91c1c;
}

#contenedorRegistros {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.panel-informativo {
    background-color: #ffffff;
    border: 2px solid #16a34a;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.12);
}

.titulo-principal {
    margin: 0 0 15px;
    color: #b91c1c;
    font-size: 27px;
    text-align: center;
}

.subtitulo-informacion {
    color: #444444;
    font-size: 16px;
    line-height: 1.9;
    margin: 0;
}

.separador-titulo {
    border: 0;
    border-top: 2px solid #16a34a;
    margin-top: 18px;
}

.fila-registro {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) minmax(80px, 0.5fr) minmax(90px, 0.6fr) minmax(180px, 2fr);
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    background-color: #ffffff;
}

.fila-registro:hover {
    background-color: #f0fdf4;
}

.fila-registro label {
    color: #b91c1c;
    font-weight: bold;
    overflow-wrap: anywhere;
}

.encabezado-tabla {
    background-color: #dcfce7;
    border: 2px solid #16a34a;
    font-weight: bold;
    color: #166534;
}

.fila-registro input[type="number"],
.fila-registro input[type="text"] {
    width: 100%;
    min-width: 0;
    padding: 10px;
    border: 1px solid #86efac;
    border-radius: 6px;
    background-color: #ffffff;
    color: #333333;
    font-size: 14px;
    outline: none;
}

.fila-registro input:focus,
.campo-logro:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
}

label[for="inputLogro"] {
    color: #b91c1c;
    font-size: 18px;
    font-weight: bold;
    margin-top: 25px;
}

.campo-logro {
    display: block;
    width: 100%;
    max-width: 850px;
    min-height: 110px;
    padding: 12px;
    margin-top: 10px;
    border: 2px solid #16a34a;
    border-radius: 8px;
    font-family: Arial, sans-serif;
    font-size: 15px;
    resize: vertical;
    outline: none;
}

button[type="submit"] {
    display: block;
    margin: 25px auto;
    padding: 13px 35px;
    background-color: #16a34a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
}

button[type="submit"]:hover {
    background-color: #15803d;
    transform: translateY(-2px);
}

button[type="submit"]:active {
    transform: scale(0.98);
}

#respuesta {
    max-width: 850px;
    margin: 20px auto;
    padding: 12px;
    border-radius: 8px;
    font-weight: bold;
    white-space: pre-line;
}

@media (max-width: 850px) {
    body {
        padding: 15px;
    }

    .fila-registro {
        grid-template-columns: minmax(150px, 1fr) 75px 85px;
        gap: 8px;
    }

    .fila-registro .campo-observacion {
        grid-column: 1 / -1;
    }

    .titulo-principal {
        font-size: 22px;
    }
}

@media (max-width: 500px) {
    body {
        padding: 10px;
    }

    .panel-informativo {
        padding: 15px;
    }

    .fila-registro {
        grid-template-columns: 1fr 1fr;
    }

    .fila-registro label,
    .fila-registro .campo-observacion {
        grid-column: 1 / -1;
    }

    .encabezado-tabla {
        display: none;
    }

    .fila-registro input {
        font-size: 13px;
    }
}
.btn-volver {
    display: block;
    width: fit-content;
    margin: 20px auto;
    padding: 10px 22px;
    background-color: #ffffff;
    color: #b91c1c;
    border: 2px solid #16a34a;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    text-align: center;
}

.btn-volver:hover {
    background-color: #16a34a;
    color: #ffffff;
}
    </style>
</head>

<body>

    <form id="formularioGrupos">
        <div id="contenedorRegistros"></div>

        <?php if ($mensaje != '') { ?>
            <p style="color:red;">
                <?php echo $mensaje; ?>
            </p>
        <?php } ?>

        <?php if (count($listaAlumnosPHP) > 0) { ?>
        <button type="submit"><strong>Subir</strong></button>
        <a href="javascript:history.back()" class="btn-volver">Volver</a>
        <?php } ?>
    </form>

    <div id="respuesta" role="status"></div>

<script>
let contenedor = document.getElementById('contenedorRegistros');
let formulario = document.getElementById('formularioGrupos');
let respuestaDiv = document.getElementById('respuesta');

let listaAlumnos = <?php
    echo json_encode(
        $listaAlumnosPHP,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    );
?>;

let cantidadRegistros = listaAlumnos.length;

let periodoActual = <?php echo (int)$Bperiodo; ?>;
let fechaCorte = <?php
    echo json_encode($BFecha, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>;

let datosEncabezado = {
    grupo: <?php echo json_encode($Bgrupo, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
    jornada: <?php echo json_encode($Bjornada, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
    periodo: periodoActual,
    docente: <?php echo json_encode($Docente, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
};

if (cantidadRegistros > 0) {

    let asignatura = listaAlumnos[0];

    let informacionSuperior = document.createElement('div');
    informacionSuperior.className = 'panel-informativo';

    let titulo = document.createElement('h2');
    titulo.className = 'titulo-principal';
    titulo.textContent = 'Cargue de Notas para el Grupo ' + datosEncabezado.grupo;

    let subtitulo = document.createElement('p');
    subtitulo.className = 'subtitulo-informacion';

    subtitulo.innerHTML =
        '<strong>Jornada:</strong> ' + datosEncabezado.jornada +
        '<br><strong>Período:</strong> ' + datosEncabezado.periodo +
        '<br><strong>Docente:</strong> ' + datosEncabezado.docente +
        '<br><strong>Asignatura:</strong> ' + asignatura.asig_nombre +
        '<br><strong>Fecha de corte:</strong> ' + fechaCorte;

    let separador = document.createElement('hr');
    separador.className = 'separador-titulo';

    informacionSuperior.appendChild(titulo);
    informacionSuperior.appendChild(subtitulo);
    informacionSuperior.appendChild(separador);
    contenedor.appendChild(informacionSuperior);

    let encabezado = document.createElement('div');
    encabezado.className = 'fila-registro encabezado-tabla';

    encabezado.innerHTML = `
        <span><strong>Estudiante</strong></span>
        <span><strong>Nota</strong></span>
        <span><strong>Inasist.</strong></span>
        <span><strong>Observación</strong></span>
    `;

    contenedor.appendChild(encabezado);
}

for (let i = 0; i < cantidadRegistros; i++) {

    let alumnoActual = listaAlumnos[i];

    let fila = document.createElement('div');
    fila.className = 'fila-registro';

    let inputNperiodo = document.createElement('input');
    inputNperiodo.type = 'hidden';
    inputNperiodo.name = `personas[${i}][nota_periodo]`;
    inputNperiodo.value = periodoActual;
    fila.appendChild(inputNperiodo);

    let inputGrupoId = document.createElement('input');
    inputGrupoId.type = 'hidden';
    inputGrupoId.name = `personas[${i}][gru_id]`;
    inputGrupoId.value = alumnoActual.gru_id;
    fila.appendChild(inputGrupoId);

    let inputEstuId = document.createElement('input');
    inputEstuId.type = 'hidden';
    inputEstuId.name = `personas[${i}][estu_id]`;
    inputEstuId.value = alumnoActual.estu_id;
    fila.appendChild(inputEstuId);

    let inputAsigId = document.createElement('input');
    inputAsigId.type = 'hidden';
    inputAsigId.name = `personas[${i}][asig_id]`;
    inputAsigId.value = alumnoActual.asig_id;
    fila.appendChild(inputAsigId);

    let labelNombre = document.createElement('label');
    labelNombre.textContent =
        alumnoActual.estu_apellido + ' ' + alumnoActual.estu_nombre;
    fila.appendChild(labelNombre);

    let inputNota = document.createElement('input');
    inputNota.type = 'number';
    inputNota.name = `personas[${i}][nota_nota]`;
    inputNota.required = true;
    inputNota.min = '0';
    inputNota.max = '5';
    inputNota.step = '0.1';
    inputNota.setAttribute('aria-label', 'Nota de ' + labelNombre.textContent);
    fila.appendChild(inputNota);

    let inputInasistencias = document.createElement('input');
    inputInasistencias.type = 'number';
    inputInasistencias.name = `personas[${i}][nota_inasistencias]`;
    inputInasistencias.required = true;
    inputInasistencias.min = '0';
    inputInasistencias.step = '1';
    inputInasistencias.setAttribute('aria-label', 'Inasistencias de ' + labelNombre.textContent);
    fila.appendChild(inputInasistencias);

    let inputFecha = document.createElement('input');
    inputFecha.type = 'hidden';
    inputFecha.name = `personas[${i}][nota_fechacorte]`;
    inputFecha.value = fechaCorte;
    fila.appendChild(inputFecha);

    let inputObservacion = document.createElement('input');
    inputObservacion.type = 'text';
    inputObservacion.name = `personas[${i}][nota_observacion]`;
    inputObservacion.className = 'campo-observacion';
    inputObservacion.setAttribute('aria-label', 'Observación de ' + labelNombre.textContent);
    fila.appendChild(inputObservacion);

    contenedor.appendChild(fila);
}

if (cantidadRegistros > 0) {

    let periodoLogro = {
        1: 'asig_logro1',
        2: 'asig_logro2',
        3: 'asig_logro3',
        4: 'asig_logro4'
    };

    let nombreLogro = periodoLogro[periodoActual];

    if (nombreLogro) {

        let labelLogro = document.createElement('label');
        labelLogro.textContent =
            'Logro previsto para ' + listaAlumnos[0].asig_nombre +
            ' en el período ' + periodoActual;
        labelLogro.setAttribute('for', 'inputLogro');
        labelLogro.style.display = 'block';
        labelLogro.style.marginTop = '2rem';

        let inputLogro = document.createElement('textarea');
        inputLogro.id = 'inputLogro';
        inputLogro.name = `personas[0][${nombreLogro}]`;
        inputLogro.required = true;
        inputLogro.rows = 4;
        inputLogro.className = 'campo-logro';
        inputLogro.value = listaAlumnos[0][nombreLogro] || '';

        contenedor.appendChild(labelLogro);
        contenedor.appendChild(inputLogro);
    }
}

formulario.addEventListener('submit', function(e) {

    e.preventDefault();

    respuestaDiv.style.color = '#333';
    respuestaDiv.textContent = 'Guardando notas...';

    let datosFormulario = new FormData(formulario);

    fetch('Addnota3F.php', {
        method: 'POST',
        body: datosFormulario
    })
    .then(async function(respuesta) {

        let mensaje = await respuesta.text();

        if (!respuesta.ok) {
            throw new Error(mensaje);
        }

        return mensaje;
    })
    .then(function(mensaje) {

        respuestaDiv.style.color = 'green';
        respuestaDiv.textContent = mensaje;

    })
    .catch(function(error) {

        respuestaDiv.style.color = 'red';
        respuestaDiv.textContent = 'Error al guardar: ' + error.message;

    });
});
</script>

</body>
</html>


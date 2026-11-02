<?php
// 1. EL PHP VA PRIMERO: Lógica y preparación de datos
require('conexion.php');
// Hacemos la conexion con la base de datos externa
$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_connect_error()); 
mysqli_set_charset($con, "utf8"); 

// creamos la matriz de los grupos
$consulta1 = "SELECT gru_id, gru_nombre, gru_jornada FROM grupos order by gru_id ASC";
$resconsulta1 = mysqli_query($con, $consulta1);
$listgrupos = [];
if ($resconsulta1 && mysqli_num_rows($resconsulta1) > 0){ 
    while ($fila = mysqli_fetch_assoc($resconsulta1)) {
        // $fila contiene los datos de cada grupo
        $listgrupos[] = $fila; 
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla para Profesores</title>
    <script src="../SweetAlert2/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f5f7f6;
    color: #333;
    min-height: 100vh;
    padding: 40px 20px;
}

.contenedor {
    width: 100%;
    max-width: 650px;
    margin: 30px auto;
    padding: 35px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(22, 163, 74, 0.12);
}

h2 {
    color: #b91c1c;
    text-align: center;
    font-size: 28px;
    margin-bottom: 12px;
}

.descripcion {
    text-align: center;
    color: #666;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 30px;
}

.formulario {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.campo {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

label {
    font-size: 15px;
    font-weight: bold;
    color: #333;
}

select,
input[type="date"] {
    width: 100%;
    padding: 13px 14px;
    font-size: 15px;
    color: #333;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

select:focus,
input[type="date"]:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
}

select:disabled {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
}

.botones {
    display: flex;
    gap: 15px;
    margin-top: 8px;
}

button,
.btn-volver {
    flex: 1;
    padding: 14px 18px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}

.btn-submit {
    background: #16a34a;
    color: white;
}

.btn-submit:hover {
    background: #15803d;
    transform: translateY(-1px);
}

.btn-volver {
    background: #e5e7eb;
    color: #374151;
}

.btn-volver:hover {
    background: #d1d5db;
}

.nota {
    margin-top: 25px;
    padding: 14px;
    background: #f0fdf4;
    border-left: 4px solid #16a34a;
    border-radius: 6px;
    color: #166534;
    font-size: 14px;
    line-height: 1.6;
}

@media (max-width: 600px) {
    body {
        padding: 20px 12px;
    }

    .contenedor {
        padding: 25px 20px;
        margin: 15px auto;
    }

    h2 {
        font-size: 24px;
    }

    .botones {
        flex-direction: column;
    }
}
</style>
<!-- http://localhost/Actaentrega1.php -->
<!-- http://localhost/Actaentrega2.php -->
	
    <h2>Planillas para Docentes</h2>

<!-- Configuramos el formulario para que apunte directamente al archivo PHP -->
<form id="formularioEscolar" action="Actaentrega2.php" method="POST">
    
    <label for="Jornad">Jornada:</label>
    <select id="Jornad" name="Jornad">
        <option value="" disabled selected>Seleccione una jornada...</option>
    </select>
    <br><br>

    <label for="Grupo">Grupo:</label>
    <select id="Grupo" name="Grupo" disabled>
        <option value="" disabled selected>Primero elija una jornada...</option>
    </select>
    <br><br>

    <label for="Periodo">Periodo:</label>
    <select id="Periodo" name="Periodo">
        <option value="" disabled selected>Seleccione un periodo...</option>
        <option value="1">Primer Periodo</option>
        <option value="2">Segundo Periodo</option>
        <option value="3">Tercer Periodo</option>
        <option value="4">Cuarto Periodo</option>
    </select>
    <br><br>

    <label for="Fcorte">Fecha Estimada de Corte:</label>
    <input type="date" id="Fcorte" name="Fcorte">
    <br><br>

    <!-- Al dar clic aquí, el navegador envía todo automáticamente -->
    <button type="button" class="btn-volver" onclick="window.location.href='../Modulos/Necesidades docente.html';">Volver</button>
    <button type="submit" class="btn-submit">Enviar</button>
</form>

    <script>
        let matrizJornadas = ['Mañana', 'Tarde', 'Noche'];
        let matrizGrupos = <?= json_encode($listgrupos, JSON_UNESCAPED_UNICODE) ?>;

        let selectJornada = document.getElementById('Jornad');
        let selectGrupo = document.getElementById('Grupo');
        let selectPeriodo = document.getElementById('Periodo');
        let inputFechaCorte = document.getElementById('Fcorte');
        let formulario = document.getElementById('formularioEscolar');

        matrizJornadas.forEach(jornada => {
            let option = document.createElement('option');
            option.value = jornada;
            option.textContent = jornada;
            selectJornada.appendChild(option);
        });

        selectJornada.addEventListener('change', function() {
            let jornadaSeleccionada = this.value.trim().toLowerCase();

            selectGrupo.innerHTML = '<option value="" disabled selected>Seleccione un grupo...</option>';

            let gruposFiltrados = matrizGrupos.filter(grupo =>
                grupo.gru_jornada &&
                grupo.gru_jornada.trim().toLowerCase() === jornadaSeleccionada
            );

            if (gruposFiltrados.length > 0) {
                selectGrupo.disabled = false;

                gruposFiltrados.forEach(grupo => {
                    let option = document.createElement('option');
                    option.value = grupo.gru_nombre;
                    option.textContent = grupo.gru_nombre;
                    selectGrupo.appendChild(option);
                });
            } else {
                selectGrupo.disabled = true;

                Swal.fire({
                    icon: 'info',
                    title: 'Sin grupos',
                    text: 'No se encontraron grupos para la jornada seleccionada.',
                    confirmButtonColor: '#16a34a'
                });
            }
        });

        selectGrupo.addEventListener('change', function() {
            let grupoNombreSeleccionado = this.value;
            let grupoEncontrado = matrizGrupos.find(g => g.gru_nombre === grupoNombreSeleccionado);
            let grupoSeleccionadoId = grupoEncontrado ? grupoEncontrado.gru_id : null;

            console.log("Grupo seleccionado ID:", grupoSeleccionadoId);
        });

        formulario.addEventListener('submit', function(event) {
            event.preventDefault();

            if (selectJornada.value === "" ||
                selectGrupo.value === "" ||
                selectPeriodo.value === "" ||
                inputFechaCorte.value === "") {

                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, completa todos los campos.',
                    confirmButtonColor: '#16a34a'
                });

                return;
            }

            Swal.fire({
                icon: 'question',
                title: '¿Generar acta?',
                text: 'Se generará la planilla del grupo seleccionado.',
                showCancelButton: true,
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#b91c1c'
            }).then((resultado) => {
                if (resultado.isConfirmed) {
                    formulario.submit();
                }
            });
        });
    </script>

</body>
</html>

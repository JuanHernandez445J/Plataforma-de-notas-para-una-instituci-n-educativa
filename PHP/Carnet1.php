
<?php

require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_connect_error());

mysqli_set_charset($con, "utf8");

$consulta1 = "SELECT gru_id, gru_nombre, gru_jornada FROM grupos ORDER BY gru_id ASC";

$resconsulta1 = mysqli_query($con, $consulta1);

$listgrupos = [];

if ($resconsulta1 && mysqli_num_rows($resconsulta1) > 0) {
    while ($fila = mysqli_fetch_assoc($resconsulta1)) {
        $listgrupos[] = $fila;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carnets</title>

    <script src="../SweetAlert2/dist/sweetalert2.all.min.js"></script>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #b91c1c;
            min-height: 100vh;
            padding: 35px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contenedor {
            width: 100%;
            max-width: 550px;
            padding: 40px;
            background-color: #ffffff;
            border: 2px solid #16a34a;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.15);
        }

        h2 {
            text-align: center;
            color: #b91c1c;
            margin-bottom: 35px;
            font-size: 27px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-weight: bold;
            color: #b91c1c;
            margin-top: 5px;
        }

        select,
        input {
            width: 100%;
            padding: 13px;
            border: 2px solid #16a34a;
            border-radius: 8px;
            background-color: #ffffff;
            color: #333333;
            font-size: 15px;
            outline: none;
        }

        select:focus,
        input:focus {
            border-color: #15803d;
            box-shadow: 0 0 4px rgba(22, 163, 74, 0.25);
        }

        select:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 15px;
            background-color: #16a34a;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #15803d;
            transform: translateY(-2px);
        }

        .volver {
            display: block;
            width: 100%;
            padding: 13px;
            margin-top: 5px;
            background-color: #b91c1c;
            color: #ffffff;
            text-decoration: none;
            text-align: center;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .volver:hover {
            background-color: #991b1b;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {

            body {
                padding: 20px 12px;
            }

            .contenedor {
                padding: 25px 20px;
            }

            h2 {
                font-size: 23px;
                margin-bottom: 25px;
            }

        }

    </style>

</head>

<body>

    <div class="contenedor">

        <h2>Elaboración de Carnets</h2>

        <form id="formularioEscolar" action="Carnet2.php" method="POST">

            <label for="Jornad">Jornada:</label>

            <select id="Jornad" name="Jornad">

                <option value="" disabled selected>Seleccione una jornada...</option>

            </select>

            <label for="Grupo">Grupo:</label>

            <select id="Grupo" name="Grupo" disabled>

                <option value="" disabled selected>Primero elija una jornada...</option>

            </select>

            <label for="Fcorte">Fecha Estimada de Corte:</label>

            <input type="date" id="Fcorte" name="Fcorte">

            <button type="submit">Crear carnets</button>

            <a class="volver" href="../Modulos/Home_Admin.html">Volver</a>

        </form>

    </div>

    <script>

        let matrizJornadas = ['Mañana', 'Tarde', 'Noche'];

        let matrizGrupos = <?= json_encode($listgrupos ?? [], JSON_UNESCAPED_UNICODE) ?>;

        let formulario = document.getElementById('formularioEscolar');

        let selectJornada = document.getElementById('Jornad');

        let selectGrupo = document.getElementById('Grupo');

        let inputFechaCorte = document.getElementById('Fcorte');

        matrizJornadas.forEach(jornada => {

            let option = document.createElement('option');

            option.value = jornada;

            option.textContent = jornada;

            selectJornada.appendChild(option);

        });

        let fechaActual = new Date();

        let año = fechaActual.getFullYear();

        let mes = String(fechaActual.getMonth() + 1).padStart(2, '0');

        let dia = String(fechaActual.getDate()).padStart(2, '0');

        inputFechaCorte.min = año + '-' + mes + '-' + dia;

        selectJornada.addEventListener('change', function() {

            let jornadaSeleccionada = this.value.trim().toLowerCase();

            selectGrupo.innerHTML = '<option value="" disabled selected>Seleccione un grupo...</option>';

            selectGrupo.disabled = false;

            let gruposFiltrados = matrizGrupos.filter(grupo =>

                grupo.gru_jornada && grupo.gru_jornada.trim().toLowerCase() === jornadaSeleccionada

            );

            if (gruposFiltrados.length === 0) {

                selectGrupo.disabled = true;

                Swal.fire({
                    icon: 'warning',
                    title: 'Sin grupos',
                    text: 'No hay grupos registrados para esta jornada.'
                });

                return;

            }

            gruposFiltrados.forEach(grupo => {

                let option = document.createElement('option');

                option.value = grupo.gru_nombre;

                option.textContent = grupo.gru_nombre;

                selectGrupo.appendChild(option);

            });

        });

        selectGrupo.addEventListener('change', function() {

            let grupoNombreSeleccionado = this.value;

            let grupoEncontrado = matrizGrupos.find(g => g.gru_nombre === grupoNombreSeleccionado);

            let grupoSeleccionadoId = grupoEncontrado ? grupoEncontrado.gru_id : null;

        });

        formulario.addEventListener('submit', function(event) {

            event.preventDefault();

            if (selectJornada.value === "" || selectGrupo.value === "" || inputFechaCorte.value === "") {

                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor complete todos los campos.'
                });

                return;

            }

            if (inputFechaCorte.value < inputFechaCorte.min) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha incorrecta',
                    text: 'La fecha de corte no puede ser anterior a hoy.'
                });

                return;

            }

            Swal.fire({

                title: '¿Generar carnets?',

                text: 'Se generarán los carnets del grupo seleccionado.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#16a34a',

                cancelButtonColor: '#b91c1c',

                confirmButtonText: 'Sí, generar',

                cancelButtonText: 'Cancelar'

            }).then((resultado) => {

                if (resultado.isConfirmed) {

                    formulario.submit();

                }

            });

        });

    </script>

</body>

</html>

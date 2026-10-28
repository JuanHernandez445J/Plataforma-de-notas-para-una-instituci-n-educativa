
<?php
// 1. EL PHP VA PRIMERO: Lógica y preparación de datos
require('conexion.php');
	// Hacemos la conexion con la base de datos externa
    $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_connect_error()); 
	mysqli_set_charset($con, "utf8"); 
	
// gru_nombre='$Bgrupo' AND asig_id='$Bmateria'	

// creamos la matriz de los grupos
   	$consulta1 = "SELECT gru_id, gru_nombre, gru_jornada FROM grupos order by gru_id ASC";
    $resconsulta1 = mysqli_query ($con,$consulta1);
	$listgrupos = [];
	if ($resconsulta1 && mysqli_num_rows($resconsulta1) > 0){ 
		while ($fila = mysqli_fetch_assoc($resconsulta1)) {
		// $fila contiene los datos de cada grupo
			$listgrupos[] = $fila; 
		}
	}

// creamos la matriz de llas asignaturas
   	$consulta2 = "SELECT asig_id, asig_nombre, asig_gru_id FROM asignaturas order by asig_id ASC";
    $resconsulta2 = mysqli_query ($con,$consulta2);
	$listmaterias = [];
	if ($resconsulta2 && mysqli_num_rows($resconsulta2) > 0){ 
		while ($fila = mysqli_fetch_assoc($resconsulta2)) {
		// $fila contiene los datos de cada grupo
			$listmaterias[] = $fila; 
		}
	}


// print_r($listgrupos) . "<br>";	
//	mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargue de Notas</title>
</head>
<body>

	<!-- http://localhost/Addnota1.php -->
	<!-- http://localhost/Addnota2.php -->
	
    <h2>Formulario de Cargue de Notas</h2>

<!-- Configuramos el formulario para que apunte directamente al archivo PHP -->
<form id="formularioEscolar" action="Addnota2.php" method="POST">
    
    <label for="Jornad">Jornada:</label>
    <select id="Jornad" name="Jornad" required>
        <option value="" disabled selected>Seleccione una jornada...</option>
    </select>
    <br><br>

    <label for="Grupo">Grupo:</label>
    <select id="Grupo" name="Grupo" required disabled>
        <option value="" disabled selected>Primero elija una jornada...</option>
    </select>
    <br><br>

    <label for="Materia">Materia:</label>
    <select id="Materia" name="Materia" required disabled>
        <option value="" disabled selected>Primero elija un grupo...</option>
    </select>
    <br><br>

    <label for="Periodo">Periodo:</label>
    <select id="Periodo" name="Periodo" required>
        <option value="" disabled selected>Seleccione un periodo...</option>
        <option value="1">Primer Periodo</option>
        <option value="2">Segundo Periodo</option>
		<option value="3">Tercer Periodo</option>
		<option value="4">Cuarto Periodo</option>
    </select>
    <br><br>

    <label for="Fcorte">Fecha de Corte:</label>
    <input type="date" id="Fcorte" name="Fcorte" required>
    <br><br>

    <!-- Al dar clic aquí, el navegador envía todo automáticamente -->
     <button type="submit">Aceptar</button>
     <a id = "volver" href = "http://localhost/Plataforma-de-notas-para-una-instituci-n-educativa%20-%20copia/Modulos/Home_docente.html">Volver</a>
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
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 25px;
}

h2 {
    text-align: center;
    color: #b91c1c;
    font-size: 28px;
    margin-bottom: 25px;
}

form {
    width: 100%;
    max-width: 500px;
    padding: 30px;
    background-color: #ffffff;
    border: 2px solid #16a34a;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.15);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #b91c1c;
    font-size: 15px;
}

select,
input[type="date"] {
    width: 100%;
    padding: 12px;
    border: 1.5px solid #16a34a;
    border-radius: 7px;
    background-color: #ffffff;
    color: #333333;
    font-size: 15px;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

select:focus,
input[type="date"]:focus {
    border-color: #15803d;
    box-shadow: 0 0 5px rgba(22, 163, 74, 0.3);
}

select:disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
}

br {
    display: none;
}

button,
input[type="submit"] {
    display: block;
    width: 100%;
    padding: 13px;
    margin-top: 20px;
    border: none;
    border-radius: 7px;
    background-color: #16a34a;
    color: #ffffff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

button:hover,
input[type="submit"]:hover {
    background-color: #15803d;
    transform: translateY(-2px);
}

@media (max-width: 600px) {
    body {
        padding: 15px;
    }

    h2 {
        font-size: 23px;
    }

    form {
        padding: 20px;
    }
}

a{
    display: inline-block;
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    margin-top: 10px;
    background-color: #b91c1c;
    color: #ffffff;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
}

a:hover {
    background-color: #991b1b;
}


</style>
</form>

<script>
    // ==========================================
    // BASES DE DATOS EN MATRICES (ARRAYS DE OBJETOS)
    // ==========================================
    const matrizJornadas = ['Mañana', 'Tarde', 'Noche'];
    const matrizGrupos = <?= json_encode($listgrupos ?? []) ?>;
    const matrizMaterias = <?= json_encode($listmaterias ?? []) ?>;

    // ==========================================
    // ELEMENTOS HTML (Se agregan las 2 nuevas referencias)
    // ==========================================
    const selectJornada = document.getElementById('Jornad');
    const selectGrupo = document.getElementById('Grupo');
    const selectMateria = document.getElementById('Materia');
    const selectPeriodo = document.getElementById('Periodo');      // <- NUEVO
    const inputFechaCorte = document.getElementById('Fcorte');  // <- NUEVO

    // ==========================================
    // PASO 1: CARGAR JORNADAS INICIALES
    // ==========================================
    matrizJornadas.forEach(jornada => {
        const option = document.createElement('option');
        option.value = jornada;
        option.textContent = jornada;
        selectJornada.appendChild(option);
    });

    // ==========================================
    // PASO 2: EVENTO AL CAMBIAR JORNADA -> FILTRAR GRUPOS
    // ==========================================
    selectJornada.addEventListener('change', function() {
        const jornadaSeleccionada = this.value.toLowerCase(); 

        selectGrupo.innerHTML = '<option value="" disabled selected>Seleccione un grupo...</option>';
        selectGrupo.disabled = false;
        
        selectMateria.innerHTML = '<option value="" disabled selected>Primero elija un grupo...</option>';
        selectMateria.disabled = true;

        const gruposFiltrados = matrizGrupos.filter(grupo => 
            grupo.gru_jornada && grupo.gru_jornada.toLowerCase() === jornadaSeleccionada
        );

        gruposFiltrados.forEach(grupo => {
            const option = document.createElement('option');
            option.value = grupo.gru_nombre;       
            option.textContent = grupo.gru_nombre; 
            selectGrupo.appendChild(option);
        });
    });

    // ==========================================
    // PASO 3: EVENTO AL CAMBIAR GRUPO -> FILTRAR MATERIAS
    // ==========================================
    selectGrupo.addEventListener('change', function() {
        const grupoNombreSeleccionado = this.value; 

        selectMateria.innerHTML = '<option value="" disabled selected>Seleccione una materia...</option>';
        selectMateria.disabled = false;

        const grupoEncontrado = matrizGrupos.find(g => g.gru_nombre === grupoNombreSeleccionado);
        const grupoSeleccionadoId = grupoEncontrado ? grupoEncontrado.gru_id : null;

        const materiasFiltradas = matrizMaterias.filter(materia => 
            String(materia.asig_gru_id) === String(grupoSeleccionadoId)
        );

        materiasFiltradas.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.asig_id;       
            option.textContent = materia.asig_nombre; 
            selectMateria.appendChild(option);
        });
    });

</script>

</body>
</html>

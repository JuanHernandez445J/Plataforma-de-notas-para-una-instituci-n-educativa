<?php

require('conexion.php');

// print_r($_POST) . "<br>";
// Verificar que realmente se hayan recibido los datos

if (isset($_POST['personas']) && is_array($_POST['personas'])) {
	
    $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_connect_error()); 
	mysqli_set_charset($con, "utf8"); 			
		
    foreach ($_POST['personas'] as $indice => $alumno) {
		 	  $N_T = $alumno['nota_periodo'] ?? 'Vacío';
		 $N_gru_id = $alumno['gru_id'] ?? '';
		$N_estu_id = $alumno['estu_id'] ?? 'Vacío';
		$N_asig_id = $alumno['asig_id'] ?? 'Vacío';
		   $N_nota = $alumno['nota_nota'] ?? 'Vacío';
		$N_inasist = $alumno['nota_inasistencias'] ?? 'Vacío';
	 	 $N_fcorte = $alumno['nota_fechacorte'] ?? 'Vacío';
		  $N_obser = $alumno['nota_observacion'] ?? 'Vacío';

$consulta2 = "INSERT INTO notas(nota_periodo,nota_gru_id,nota_estu_id,nota_asig_id,nota_nota,nota_inasistencias,nota_fechacorte,nota_observacion) 
values ('$N_T', '$N_gru_id', '$N_estu_id', '$N_asig_id', '$N_nota', '$N_inasist', '$N_fcorte', '$N_obser')";

$resultado = mysqli_query($con, $consulta2) or die("Error en INSERT: " . mysqli_error($con));
$id_insert = $con->insert_id;

	}
		
		// actualizamos el logro en la BD, segun el periodo = Logro	
	  $logro = '';
	  switch ($N_T) {  // Periodo 1,2,3,4 = Logro 1,2,3,4
		case 1:
			$logro ='asig_logro1'; break;
		case 2:
			$logro ='asig_logro2'; break;
		case 3:
			$logro ='asig_logro3'; break;
		case 4:
			$logro ='asig_logro4'; break;
		default:
		   echo "logro inexistente";	
		}   
	
		// $consulta = "select asig_id, asig_nombre, ".$logro." from asignaturas WHERE asig_id=".$N_asig_id;

		$logroForm = $_POST['personas'][0];	// capturamos el logro que viene del formulario	
		$logro_F = htmlspecialchars($logroForm[$logro], ENT_QUOTES, 'UTF-8');

		$consulta = "UPDATE asignaturas SET " . $logro . " = '$logro_F' WHERE asig_id = " . (int)$N_asig_id;
		$res_update = mysqli_query($con, $consulta) or die("Error en UPDATE: " . mysqli_error($con));
			 
		//$fila = mysqli_fetch_assoc($resconsulta);  // capturamos la respuesta
		//echo $fila['asig_id'].'    '.$fila['asig_nombre'].'    '.$fila[$logro]. "<br>";		

		// echo "Nota " . htmlspecialchars($N_nota, ENT_QUOTES, 'UTF-8') . "<br>";
        // echo "Estudiante procesado con ID: " . htmlspecialchars($N_T, ENT_QUOTES, 'UTF-8') . "<br>";

 mysqli_close($con);  // Cerrar la conexión general
}
        
        

?>

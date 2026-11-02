<?php

require('conexion.php');

// Validamos que la petición sea estrictamente por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificamos si la variable existe en el arreglo $_POST
    if (isset($_POST['mi_variable'])) {
        
        // Limpiamos la variable por seguridad antes de mostrarla
        $valorLimpio = htmlspecialchars($_POST['mi_variable'], ENT_QUOTES, 'UTF-8');

	// Hacemos la conexion con la base de datos externa
    $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_connect_error()); 
	mysqli_set_charset($con, "utf8"); 
	
// borramos los datos de la tabla Usuarios	
$Consulta0 = "DELETE FROM Usuarios";
$resconsulta0 = mysqli_query ($con,$Consulta0);

// Ponemos el autoincremental de usuarios en 1
$Consulta6 = "ALTER TABLE Usuarios AUTO_INCREMENT = 1";
$resconsulta6 = mysqli_query ($con,$Consulta6);	

	// 1.Agregamos a la tabla Usuarios el usuario y clave de cada Acudiente, cuando el estudiente no tiene identificacion

$consulta4 = "SELECT estu_acud_id, estu_identificacion, acud_id, acud_identificacion FROM acudientes
			  INNER JOIN estudiantes ON acudientes.acud_id=estudiantes.estu_acud_id
			  WHERE estu_identificacion = '' OR estu_identificacion IS NULL;";
				  
	$resconsulta4 = mysqli_query ($con,$consulta4);
	if ($resconsulta4 && mysqli_num_rows($resconsulta4) > 0){  // Si encontramos información
	// Inicializas un array vacío para guardar los id, de los alumnos que no tienen identificacion 
		$lista_alumnos_sin_identificacion = [];
		while ($fffila = mysqli_fetch_assoc($resconsulta4)) {
			$lista_alumnos_sin_identificacion [] = $fffila; 
		}

	
	foreach ($lista_alumnos_sin_identificacion as $uuu ) {
		$consulta5 = "UPDATE estudiantes SET estu_identificacion = '{$uuu['acud_identificacion']}' 
		WHERE estu_acud_id= '{$uuu['acud_id']}' ";
		
			$resconsulta5 = mysqli_query($con, $consulta5) or die("Error en INSERT: " . mysqli_error($con));
			$id_insert = $con->insert_id;
		}		
	}	


	// 2.Agregamos a la tabla Usuarios el usuario y clave de cada alumno
	
	$consulta = "SELECT 'on', estu_identificacion, estu_identificacion 'estudiante'FROM estudiantes";
	$resconsulta = mysqli_query ($con,$consulta);
	if ($resconsulta && mysqli_num_rows($resconsulta) > 0){  // Si encontramos información
	// Inicializas un array vacío para guardar 
	$lista_usuarios_Alumnos = [];
	// Listamos y cargamos el Resultados en una Matriz 
		while ($fila = mysqli_fetch_assoc($resconsulta)) {
			$lista_usuarios_Alumnos [] = $fila; 
		}
		
		foreach ($lista_usuarios_Alumnos as $u ) {
			$consulta2 = "INSERT INTO usuarios (usua_activo, usua_usuario ,usua_clave, usua_rol) values ('1', '{$u['estu_identificacion']}', '{$u['estu_identificacion']}', 'estudiante' )";	
			$resconsulta2 = mysqli_query($con, $consulta2) or die("Error en INSERT: " . mysqli_error($con));
			$id_insert = $con->insert_id;
		}
	}
	
	// 3.Agregamos a la tabla Usuarios el usuario y clave de cada Docente
	
	$consulta1 = "SELECT 'on', doce_identificacion, doce_identificacion, 'docente' FROM docentes";
	$resconsulta1 = mysqli_query ($con,$consulta1);
	if ($resconsulta1 && mysqli_num_rows($resconsulta1) > 0){  // Si encontramos información
	// 1. Inicializas un array vacío para guardar los alumnos
	$lista_usuarios_Profesor = [];
	// 2. Tu bucle while actual (ejemplo con mysqli)
		while ($ffila = mysqli_fetch_assoc($resconsulta1)) {
			$lista_usuarios_Profesor [] = $ffila; 
		}
	
		foreach ($lista_usuarios_Profesor as $uu ) {
			$consulta3 = "INSERT INTO usuarios (usua_activo, usua_usuario ,usua_clave, usua_rol) values ('1', '{$uu['doce_identificacion']}', '{$uu['doce_identificacion']}', 'docente' )";		
			$resconsulta3 = mysqli_query($con, $consulta3) or die("Error en INSERT: " . mysqli_error($con));
			$id_insert = $con->insert_id;
		}		
	}
	
        // Esto es lo que el 'alert' de JavaScript va a capturar y mostrar
        // echo "¡Inicializacion de Usuarios y Contraseñas con éxito! Recibí: " . $valorLimpio;
		echo "¡Inicializacion de Usuarios y Contraseñas Realizada con éxito! ";
    } else {
        echo "Error: No se recibió la variable 'mi_variable'.";
    }
    
} else {
    echo "Error: Método de acceso no permitido.";
}
mysqli_close($con);
?>


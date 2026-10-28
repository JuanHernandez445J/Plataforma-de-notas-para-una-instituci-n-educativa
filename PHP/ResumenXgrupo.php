    <?php
	require('conexion.php');
	require('ResumenXgrupoFuncion.php');

    // 1. Verificar si el formulario fue enviado mediante el método POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 2. Capturar los datos utilizando la superglobal $_POST
        // Se usa el operador de fusión de enteros (??) para evitar errores si el campo está vacío
        //$cedula = $_POST['Grupo'] ?? '';
		
			  $grup = $_POST['Grupo'] ?? '';
			$Bjorna = $_POST['Jorna'] ?? '';
			$Mjornada = strtoupper($Bjorna); // Mayuscula

			// Sanitizar los datos para prevenir ataques XSS (Inyección de código)
			$Bgrupo = htmlspecialchars($grup, ENT_QUOTES, 'UTF-8');
			$Bjornada = htmlspecialchars($Mjornada, ENT_QUOTES, 'UTF-8');
			
			if (!empty($Bgrupo)) {
            error_reporting ( E_ALL  ^  E_NOTICE  ^  E_DEPRECATED );
			
			// Hacemos la conexion con la base de datos externa
            $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_error($con)); 
            mysqli_set_charset($con, "utf8"); 
			
			// traemos la informacion de los grupos

			$consulta = " SELECT estu_identificacion 
						  FROM estudiantes inner join grupos on estudiantes.estu_gru_id = grupos.gru_id
						  WHERE gru_nombre = '$Bgrupo' AND gru_jornada = '$Bjornada' order by estu_apellido";	
            $resconsulta = mysqli_query ($con,$consulta);
			if ($resconsulta && mysqli_num_rows($resconsulta) > 0){  // Si encontramos información
				$listcedulas= mysqli_fetch_all($resconsulta);

				$lineas = [];
				foreach ($listcedulas as $identificacion) {
					// Une los campos de cada estudiante con un guion corto
					$lineas[] = implode(", ", $identificacion);
				}
				$estudiantes_a_procesar = $lineas;
				GenerarBoletinMasivo($estudiantes_a_procesar);	
			} // resultado de la consulta
			else {
				echo "<p style='color:red;'>No Existen Datos para esa Consulta.</p>";
			}
			}  // de vacio la entrada

//        ConcentradorNdoc($ced);
	
		
		     } // server

    ?>

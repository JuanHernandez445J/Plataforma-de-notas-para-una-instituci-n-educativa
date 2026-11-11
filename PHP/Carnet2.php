<?php
// 1. Cargar las dependencias necesarias
require('../fpdf186/fpdf.php');
require('FPrinter.php');
require('conexion.php'); // Asegura que este archivo defina las variables $db_host, $db_login, $db_pswd, $db_name

class PDF extends FPDF {
    // Pie de página con borde institucional
    function Footer() {
        $this->SetLineWidth(0.8);
        $this->SetDrawColor(0, 0, 0);
        $margenBorde = 8;
        $this->Rect(
            $margenBorde, 
            $margenBorde, 
            $this->GetPageWidth() - ($margenBorde * 2), 
            $this->GetPageHeight() - ($margenBorde * 2)
        );
    }
}

// 2. Verificar envío del formulario por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // 2. Capturar los datos utilizando $_POST
        // Se usa el operador de fusión de enteros (??) para evitar errores si el campo está vacío
				 
			$Bjornada = $_POST['Jornad'] ?? '';
			   $Bgrupo = $_POST['Grupo'] ?? '';
		  	   $BFecha = $_POST['Fcorte'] ?? '';
        
        // Conexión segura a la Base de Datos
        $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);
        if (!$con) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        mysqli_set_charset($con, "utf8");
        
        // Obtener información de la sede de manera asociativa
        $consede = "SELECT * FROM sede LIMIT 1";
        $reconsede = mysqli_query($con, $consede);
        $sede = mysqli_fetch_assoc($reconsede);

        // Si encontramos información del estudiante, procedemos a generar el PDF (UNA SOLA VEZ)

			$pdf = new PDF('P', 'mm', 'Letter');
            // $pdf = new PDF('P', 'mm', 'Legal');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            
            // --- ENCABEZADO INSTITUCIONAL ---
            //$pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFont('Arial', '', 8);

			
            // --- IMÁGENES Y LOGOS ---

          
            $consulta = 
			" SELECT 
	
				gru_id, 
				gru_jornada, 
				gru_nombre, 
				
				estu_id,
                estu_foto,
				estu_apellido,
				estu_nombre,
				estu_identificacion

                FROM estudiantes
                INNER JOIN grupos ON grupos.gru_id=estudiantes.estu_gru_id 
                WHERE gru_jornada='$Bjornada' AND gru_nombre='$Bgrupo'
                GROUP BY estudiantes.estu_id
                ORDER BY estu_apellido";
				
            $resconsulta = mysqli_query($con, $consulta);
            if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {
				// 2. Extraemos la primera fila, una única vez
				$row = mysqli_fetch_assoc($resconsulta);
				
				$line='';
			
            // Cabecera 
			$pdf->SetFont('Courier', 'B', 11); 
			// $pdf->ln(3);
   				mysqli_data_seek($resconsulta, 0);  // devolvemos el puntero al inicio

//				            $pdf->SetFont('Courier', '', 11);
            // $pdf->SetFillColor(200, 220, 255);
			$pdf->SetFillColor(220, 220, 220);

				$sede2 = str_replace("INSTITUCION EDUCATIVA", "I.E.", $sede['sede_nombre']);
                while ($fila = mysqli_fetch_assoc($resconsulta)) {
					$pdf->ln(3);
					$pdf->SetFont('Courier', '', 10); 
					
					$y_actual = $pdf->GetY();
					if (file_exists("LogoColegio.png")) $pdf->Image("LogoColegio.png", 14, $y_actual, 9);
					if (file_exists("LogoAlcaldia.png")) $pdf->Image("LogoAlcaldia.png", 90, $y_actual, 9);
							
					$pdf->Cell(90, 5, utf8_decode($sede2), 0, 1, 'C'); 
					$pdf->Cell(90, 4, utf8_decode(' Tel: ' . ($sede['sede__telefono'])), 0, 1, 'C'); 
					$pdf->Cell(90, 4, utf8_decode($sede['sede_email']), 0, 1, 'C'); 
            
					$pdf->Cell(90, 1, '________________________', 0, 1, 'C');
					$pdf->Cell(90, 3, ' ', 0, 1, 'C');
					$pdf->Cell(90, 5, 'CARNET ESTUDIANTIL ', 0, 1, 'C');

				$Nomgrupo = 'Grupo : '.$row['gru_nombre'];
				$line = utf8_decode(str_pad($Nomgrupo,14));
				
				$Nomjornada = $line.'     Jornada : '.$row['gru_jornada'];
				$line = utf8_decode(str_pad($Nomjornada,30));
				$pdf->Cell(90, 4, $line, 0, 1, 'L');
				$pdf->Cell(90, 4, 'Estudiante', 0, 1, 'L');
				
				// 1. Guardamos la posición Y actual antes de que las celdas sigan bajando
					$y_actual = $pdf->GetY();
	
					if (file_exists('../Fotos/' .$fila['estu_foto'])) $pdf->Image('../Fotos/' .$fila['estu_foto'], 88, $y_actual-2, 12);
					$nombre = $fila['estu_apellido'] . " " . $fila['estu_nombre'];
					$line = str_pad($nombre, 36);
					$pdf->Cell(90, 4, $line, 0, 0, 'L');
					$pdf->Cell(67, 4, '__________________', 0, 1, 'R');
					$pdf->Cell(90, 4, 'Identificacion', 0, 0, 'L');
					$pdf->Cell(65, 4, 'Firma Autorizada', 0, 1, 'R');
					$pdf->Cell(90, 4, $fila['estu_identificacion'], 0, 1, 'L');
					
					$line = utf8_decode(str_pad('Caducidad : ', 5).$BFecha);
					$pdf->Cell(0, 6, $line, 0, 1, 'L'); 
					$pdf->Cell(0, 1, ' ', 0, 1, 'L',true); // Pequeño espacio de separación
					$pdf->ln(2);
                 }
            }

 $lineFinal = '';
                // Imprimir fila con fondo alternado interactivo (opcional, aquí marcado como true)
               // $pdf->Cell(0, 5, utf8_decode($lineFinal), 0, 1, 'L', true); 

            $Filename = 'Planilla_de_Notas.pdf';
            if (file_exists($Filename)) { 
                @unlink($Filename); 
            }
            $pdf->Output('I', $Filename);

        mysqli_close($con);
    
}
?>

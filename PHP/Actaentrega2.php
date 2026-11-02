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
			$Bperiodo = $_POST['Periodo'] ?? ''; 
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
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 5, utf8_decode($sede['sede_nombre'] ?? 'INSTITUCIÓN EDUCATIVA'), 0, 1, 'C'); 
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 5, 'NIT: ' . ($sede['sede_nit'] ?? ''), 0, 1, 'C'); 
            $pdf->Cell(0, 5, utf8_decode($sede['sede_direccion'] ?? ''), 0, 1, 'C'); 
            $pdf->Cell(0, 5, utf8_decode(($sede['sede_email'] ?? '') . ' Tel: ' . ($sede['sede__telefono'] ?? '')), 0, 1, 'C'); 
            $pdf->Cell(0, 5, utf8_decode($sede['sede__observacion'] ?? ''), 0, 1, 'C'); 
            
            $pdf->Cell(130, 3, '________________________________', 0, 1, 'R');
            $pdf->Cell(125, 3, ' ', 0, 1, 'R');
            $pdf->Cell(125, 3, 'ACTA DE ENTREGA DE BOLETINES', 0, 1, 'R');
			
			
            // --- IMÁGENES Y LOGOS ---
            if (file_exists("LogoColegio.png")) $pdf->Image("LogoColegio.png", 14, 10, 12);
            if (file_exists("LogoAlcaldia.png")) $pdf->Image("LogoAlcaldia.png", 187, 10, 12);

          
            $consulta = 
			" SELECT 
				gru_id, 
				gru_jornada, 
				gru_nombre, 
				gru_doce_id,
				estu_id,
				estu_apellido,
				estu_nombre,
				
				doce_apellido,
				doce_nombre 

                FROM estudiantes
                INNER JOIN grupos ON grupos.gru_id=estudiantes.estu_gru_id 
                INNER JOIN docentes ON docentes.doce_id=Grupos.gru_doce_id 
                WHERE gru_jornada='$Bjornada' AND gru_nombre='$Bgrupo' 
                GROUP BY estudiantes.estu_id
                ORDER BY estu_apellido";
				
            $resconsulta = mysqli_query($con, $consulta);
            if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {
				// 2. Extraemos la primera fila, una única vez
				$row = mysqli_fetch_assoc($resconsulta);
				
				$line='';
				$pdf->ln(4);
				$Nomgrupo = 'Grupo : '.$row['gru_nombre'];
				$line = utf8_decode(str_pad($Nomgrupo,14));
				
				$Nomjornada = '     Jornada : '.$row['gru_jornada'];
				$line = $line.utf8_decode(str_pad($Nomjornada,30));
				
				$Nomperiodo = '   Periodo : '.$Bperiodo;
				$line = $line.utf8_decode(str_pad($Nomperiodo,20));

				$NFecha = "       Fecha de Corte : _____________";   //.$BFecha;
				$line = $line.utf8_decode(str_pad($NFecha,30));
				
				$pdf->Cell(125, 5, $line, 0, 1, 'L');
				
	
				$Adoce = $row['doce_apellido'];
				$Ndoce = $row['doce_nombre'];
				$Docente = "Director de Grupo: " .mb_strtoupper($Adoce).' '.mb_strtoupper($Ndoce).'   ';
				$line = utf8_decode(str_pad($Docente,35));
				$pdf->ln(2);
				$pdf->Cell(125, 3, $line, 0, 1, 'L');
				$pdf->ln(8);
//				$pdf->Cell(125, 3, ' ', 0, 1, 'L');
				
            // Cabecera 
			$pdf->SetFont('Courier', 'B', 11); 
			$line=str_pad('ID  ESTUDIANTE ',48);
			$pdf->Cell(48, 0,$line, 0, 1, 'L');
			
			$line='  FIRMA ACUDIENTE';
			$pdf->Cell(212, 0,$line, 0, 1, 'C');
			
			$line='  TELEFONO';
			$pdf->Cell(175, 0,$line, 0, 1, 'R');
			
			$pdf->ln(3);
   				mysqli_data_seek($resconsulta, 0);  // devolvemos el puntero al inicio

//				            $pdf->SetFont('Courier', '', 11);
            $pdf->SetFillColor(200, 220, 255);

                while ($fila = mysqli_fetch_assoc($resconsulta)) {
					$pdf->SetFont('Courier', '', 10); 

					$Id = $fila['estu_id'];
					$line = str_pad($Id, 4);
					
					$nombre = $line.$fila['estu_apellido'] . " " . $fila['estu_nombre'];
					$line = str_pad($nombre, 36);
					$pdf->Cell(0, 6, $line, 0, 1, 'L'); 
					$pdf->Cell(0, 2, ' ', 0, 1, 'L',true); // Pequeño espacio de separación
                 }
            }

 $lineFinal = '';
                // Imprimir fila con fondo alternado interactivo (opcional, aquí marcado como true)
               // $pdf->Cell(0, 5, utf8_decode($lineFinal), 0, 1, 'L', true); 
         
            
            // --- SECCIÓN DE FIRMAS ---
            $pdf->Ln(150);
            $pdf->Cell(200, 5, '____________________________', 0, 1, 'C');
            $pdf->Cell(200, 5, utf8_decode('Director de Grupo'), 0, 1, 'C');
            $docenteNombre = $row['doce_nombre'] . ' ' . $row['doce_apellido'];
            $pdf->Cell(200, 5, utf8_decode($docenteNombre), 0, 1, 'C'); 
            
            // Descarga o renderizado del documento
            $Filename = 'Planilla_de_Notas.pdf';
            if (file_exists($Filename)) { 
                @unlink($Filename); 
            }
            $pdf->Output('I', $Filename);
            

        
        mysqli_close($con);
    
}
?>

<?php
require('../fpdf186/fpdf.php');
require('FPrinter.php');

class PDF extends FPDF {
    // Pie de página institucional
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
        
        $this->SetY(-15);   
        $this->SetFont('Arial', 'I', 8);  
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R'); 
    }
}
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Captura y sanitización directa con fusión de datos
    $numcedula     = htmlspecialchars(trim($_POST['Ndoc'] ?? ''), ENT_QUOTES, 'UTF-8');
    $Busca_periodo = intval($_POST['Ciclo'] ?? 0);

    if (!empty($numcedula) && $Busca_periodo > 0) {
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

        $db_host  = "localhost";    
        $db_login = "root";
        $db_pswd  = "";
        $db_name  = "Boletines";  

        $con = mysqli_connect($db_host, $db_login, $db_pswd, $db_name);
        if (!$con) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        mysqli_set_charset($con, "utf8"); 

        // Consulta de la Sede optimizada (Solo pedimos la primera fila necesaria)
        $consede   = "SELECT * FROM sede LIMIT 1";
        $reconsede = mysqli_query($con, $consede);
        $sede      = mysqli_fetch_row($reconsede); // fetch_row es más rápido si solo es una fila
        
        // Consulta de Notas
        $consulta = "SELECT nota_periodo, gru_jornada, gru_nombre, DATE_FORMAT(nota_fechacorte,'%d/%m/%Y') AS fecha_corte,
                            estu_foto, estu_nombre, estu_apellido, asig_nombre, doce_nombre, doce_apellido,
                            asig_intensidad, nota_nota, nota_inasistencias, asig_logro1, asig_logro2, asig_logro3, asig_logro4, nota_observacion
                     FROM notas 
                     INNER JOIN estudiantes ON notas.nota_estu_id=estudiantes.estu_id 
                     INNER JOIN asignaturas ON notas.nota_asig_id=asignaturas.asig_id 
                     INNER JOIN docentes ON asignaturas.asig_doce_id=docentes.doce_id 
                     INNER JOIN grupos ON grupos.gru_id=notas.nota_gru_id 
                     WHERE estu_identificacion='$numcedula' AND nota_periodo='$Busca_periodo' 
                     ORDER BY asig_nombre";

        $resconsulta = mysqli_query($con, $consulta);

        if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {  
            $fila = mysqli_fetch_all($resconsulta, MYSQLI_NUM);	
            mysqli_close($con); // Cerramos la conexión temprano para liberar recursos

            // Mapeo rápido del periodo usando un array indexado (reemplaza el switch)
            $periodos_nombres = [
                1 => "PRIMER PERIODO",
                2 => "SEGUNDO PERIODO",
                3 => "TERCER PERIODO",
                4 => "CUARTO PERIODO"
            ];
            $periodo = $periodos_nombres[$fila[0][0]] ?? "PERIODO INEXISTENTE";

            // Inicialización de FPDF
			// $pdf = new PDF('P', 'mm', 'Letter');
			$pdf = new PDF('P', 'mm', 'Legal');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetLineWidth(1);
            $pdf->SetFillColor(200, 220, 255);
            
            // Encabezado institucional
            $pdf->SetFont('Arial', 'B', 12); 						
            $pdf->Cell(0, 2, utf8_decode($sede[1]), 0, 1, 'C');			
            $pdf->SetFont('Arial', '', 10); 						
            $pdf->Cell(0, 7, 'NIT ' . $sede[2], 0, 1, 'C'); 	
            $pdf->Cell(0, 4, utf8_decode($sede[3]), 0, 1, 'C');			
            $pdf->Cell(0, 4, utf8_decode($sede[4]) . '       Tel : ' . $sede[5], 0, 1, 'C');	
            $pdf->Cell(0, 4, utf8_decode($sede[6]), 0, 1, 'C');			
            $pdf->Cell(130, 3, '____________________________________', 0, 1, 'R');
            $pdf->Cell(130, 3, ' ', 0, 1, 'R');

            // Logos e Imagen de perfil
            $pdf->Image("LogoColegio.png", 14, 10, 12);    
            $pdf->Image("LogoAlcaldia.png", 187, 10, 12);  
            if (!empty($fila[0][4])) {
                $pdf->Image('../Fotos/' . $fila[0][4], 14, 29, 15);  		
            }

            // Datos del Estudiante (Corregido índice base 0)
			$line = utf8_decode($fila[0][6] . " " . $fila[0][5]);
            $line = str_pad($line, 45, " "); 
			$pdf->Cell(98, 7, $line, 0, 0, 'R');
			
			$line = utf8_decode("JORNADA DE LA : " . $fila[0][1] . '        ' . "GRADO :" . $fila[0][2]);
            $line  = str_pad($line, 90, " "); 			
			
			$pdf->Cell(100, 7, $line, 0, 1, 'C');			 
			
            // $pdf->Cell(78, 7, utf8_decode($fila[0][6] . " " . $fila[0][5]), 0, 0, 'R');  
            // $pdf->Cell(85, 7, utf8_decode("JORNADA DE LA : " . $fila[0][1] . '        ' . "GRADO :" . $fila[0][2]), 0, 1, 'R');			 
						
            $pdf->Cell(63, 5, "INFORME DE NOTAS ", 0, 0, 'R');
            $pdf->Cell(56, 5, ' ' . $periodo, 0, 0, 'R');    
            $pdf->Cell(55, 5, ' FECHA : ' . $fila[0][3], 0, 1, 'R');    
			
            // Configuración de tabla de calificaciones con Courier (Alineación exacta)
            $pdf->SetFont('Courier', 'B', 11); 	
            $encabezado = 'MATERIA                             DOCENTE                    IH DESEMPEÑO   I';
            $pdf->Cell(0, 5, utf8_decode($encabezado), 0, 1, 'L');   
            
            $pdf->SetFont('Courier', '', 10); 
            $total_materias = count($fila);
				
            for ($jj = 0; $jj < $total_materias; $jj++) {
                $pdf->Cell(0, 4, ' ', 0, 1, 'L');

                // CORRECCIÓN DE ALINEACIÓN: utf8_decode antes de procesar el str_pad
                $materia_fmt = utf8_decode($fila[$jj][7]);
                $docente_fmt = utf8_decode($fila[$jj][9] . ' ' . $fila[$jj][8]);

                $line = str_pad($materia_fmt, 35, " "); 
                $line .= str_pad($docente_fmt, 35, " ");  
                $line .= str_pad($fila[$jj][10], 3, " ");    
					
                $nota = floatval($fila[$jj][11]);
                $rendimiento = '(Bajo)';
                if ($nota >= 4.6) { $rendimiento = '(Supe)'; }
                elseif ($nota >= 4.0) { $rendimiento = '(Alto)'; }
                elseif ($nota >= 3.0) { $rendimiento = '(Basi)'; }
					
                $line .= str_pad($fila[$jj][11] . ' ' . $rendimiento, 13, " ");  
                $line .= str_pad($fila[$jj][12], 2, " ");					 
					
                $pdf->Cell(0, 4, $line, 0, 1, 'L', true); 
				
				switch ($Busca_periodo) {
					case 1:	$pdf->MultiCell(0, 4, utf8_decode($fila[$jj][13]), 0, 'L');
						break;
					case 2:	$pdf->MultiCell(0, 4, utf8_decode($fila[$jj][14]), 0, 'L');
						break;
					case 3:	$pdf->MultiCell(0, 4, utf8_decode($fila[$jj][15]), 0, 'L');
						break;
					case 4:	$pdf->MultiCell(0, 4, utf8_decode($fila[$jj][16]), 0, 'L');
						break;
				}
                $pdf->MultiCell(0, 4, utf8_decode('Observacion : ' . $fila[$jj][17]), 0, 'L');  
            }
				
            // Envío del documento
            $Filename = 'Reporte_de_Notas.pdf'; 
            $pdf->Output('I', $Filename);  
            exit;
        } else {
            mysqli_close($con);
            echo "<p style='color:red;'>NO EXISTEN DATOS POR ESTA CONSULTA.</p>";
        }
    } else {
        echo "<p style='color:red;'>Existe un error en sus datos de entrada.</p>";
    }
} else {
    echo "<p>Esperando el envío del formulario...</p>";
}
?>

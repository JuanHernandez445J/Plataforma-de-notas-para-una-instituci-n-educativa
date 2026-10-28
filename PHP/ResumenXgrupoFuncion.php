<?php
require('../fpdf186/fpdf.php');
require('FPrinter.php');

// ==========================================
// MODO DE USO
// ==========================================
// Defines tus cédulas en un arreglo y llamas a la función una sola vez.
// $estudiantes_a_procesar = array('53072304', '53069917');
// GenerarBoletinMasivo($estudiantes_a_procesar);

// 1. SOLUCIÓN: La función ahora recibe un arreglo con múltiples cédulas
	class PDF extends FPDF{
    // Pie de página
    function Footer() {
		
        // Crear un borde para la pagina - Establecer el grosor de la línea del borde (en mm)
        $this->SetLineWidth(0.8);
        
        // Establecer el color del borde (RGB)
        $this->SetDrawColor(0, 0, 0); 
        
        // Margen que quieres que tenga el borde respecto al extremo del papel
        $margenBorde = 8; 
        
        // Dibujar el rectángulo de la página completa
        $this->Rect(
            $margenBorde, 
            $margenBorde, 
            $this->GetPageWidth() - ($margenBorde * 2), 
            $this->GetPageHeight() - ($margenBorde * 2)
        );		
     }
}

function GenerarBoletinMasivo($lista_cedulas) {
    global $servidor, $usuario, $contraseña, $base_datos;

    // Instanciar FPDF una sola vez afuera de todos los ciclos
    $pdf = new PDF('P', 'mm', 'Letter');
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 15); 

    // BUCLE 1: Recorrer el arreglo dinámico de estudiantes enviados
    foreach ($lista_cedulas as $cedula) {
        
        $numcedula = htmlspecialchars(trim($cedula), ENT_QUOTES, 'UTF-8');

        if (!empty($numcedula)) {
            // Abrir conexión limpia para este estudiante
            $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_error($con)); 
            mysqli_set_charset($con, "utf8"); 

            // Estructura en memoria para organizar materias y periodos
            $boletin = array();
            $datos_estudiante = null;

            // Información del colegio
            $consede = "SELECT * FROM sede";
            $reconsede = mysqli_query($con, $consede);
            $sede = mysqli_fetch_all($reconsede);

            // BUCLE 2: Consultar periodos consecutivamente
            for ($i_periodo = 1; $i_periodo <= 4; $i_periodo++) {
                $consulta = "SELECT nota_periodo,
                             gru_jornada, 
                             gru_nombre, 
                             estu_nombre,
                             estu_foto,
                             estu_apellido,
                             asig_nombre, 
                             nota_nota,
                             doce_nombre,
                             doce_apellido
                    FROM notas 
                    INNER JOIN estudiantes ON notas.nota_estu_id=estudiantes.estu_id 
                    INNER JOIN asignaturas ON notas.nota_asig_id=asignaturas.asig_id 
                    INNER JOIN grupos ON grupos.gru_id=notas.nota_gru_id 
                    INNER JOIN docentes ON docentes.doce_id=grupos.gru_doce_id 
                    WHERE estu_identificacion='$numcedula' AND nota_periodo='$i_periodo' 
                    ORDER BY asig_nombre";

                $resconsulta = mysqli_query($con, $consulta);
                
                if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {  
                    while ($row = mysqli_fetch_assoc($resconsulta)) {
                        if (!$datos_estudiante) {
                            $datos_estudiante = $row;
                        }

                        $materia = $row['asig_nombre'];
                        
                        if (!isset($boletin[$materia])) {
                            $boletin[$materia] = array('p1' => 0, 'p2' => 0, 'p3' => 0, 'p4' => 0, 'final' => 0);
                        }

                        if ($row['nota_periodo'] == 1) $boletin[$materia]['p1'] = $row['nota_nota'];
                        if ($row['nota_periodo'] == 2) $boletin[$materia]['p2'] = $row['nota_nota'];
                        if ($row['nota_periodo'] == 3) $boletin[$materia]['p3'] = $row['nota_nota'];
                        if ($row['nota_periodo'] == 4) $boletin[$materia]['p4'] = $row['nota_nota'];
                    }
                }  
            } // Fin bucle periodos
            
            mysqli_close($con); // Cerrar conexión de forma segura

            // 2. SI EL ESTUDIANTE TIENE NOTAS, GENERAMOS SU HOJA INDEPENDIENTE EN EL PDF
            if ($datos_estudiante !== null) {
                
                $pdf->AddPage(); // Página nueva exclusiva para este alumno
                
                // Encabezados de la Institución
                $pdf->SetFont('Arial','',10); 
                $pdf->SetLineWidth(1);
                $pdf->SetFillColor(200, 220, 255);
                
                $pdf->SetFont('Arial','B',12); 
                $pdf->Cell(0, 2, $sede[0][1], 0, 1, 'C');    
                $pdf->SetFont('Arial','',10); 
                $pdf->Cell(0, 7, 'NIT '.$sede[0][2], 0, 1, 'C'); 
                $pdf->Cell(0, 4, $sede[0][3], 0, 1, 'C');    
                $pdf->Cell(0, 4, $sede[0][4].'       Tel : '.$sede[0][5], 0, 1, 'C');    
                $pdf->Cell(0, 4, $sede[0][6], 0, 1, 'C');    
                $pdf->Cell(130, 3, '________________________________', 0, 1, 'R');
                $pdf->Cell(125, 3, ' ', 0, 1, 'R');    
                $pdf->Cell(125, 3, '     CONCENTRADOR DE NOTAS', 0, 1, 'R');    
                
                $pdf->Image("LogoColegio.png",14,10,12);  
                $pdf->Image("LogoAlcaldia.png",187,10,12);  
                $pdf->Image('../Fotos/'.$datos_estudiante['estu_foto'],14,34,15);  

                // Información del Estudiante
                $pdf->SetFont('Courier','B',10); 
                $line = str_pad($datos_estudiante['estu_apellido']." ".$datos_estudiante['estu_nombre'], 35); 
                $pdf->Cell(0,8,str_pad(utf8_decode($line),80),0,1,'R'); 
                
                $line = str_pad("GRADO :".$datos_estudiante['gru_nombre'].'      '."JORNADA DE LA ".utf8_decode($datos_estudiante['gru_jornada']),35);
                $pdf->Cell(0,8,str_pad($line,80),0,1,'R');
                
                $line = utf8_decode(str_pad('ASIGNATURA                             P1     P2     P3    P4   FINAL', 60, " ", STR_PAD_LEFT)); 
                $pdf->Cell(0,4,''.$line,0,1,'L'); 
                $pdf->SetFont('Courier','',11);
                
                // Dibujar tabla de materias calificadas
                foreach ($boletin as $nombre_materia => $notas) {
                    $pdf->Cell(0,2,' ',0,1,'L'); // Pequeño espacio de separación
					
					// Formatear columnas alineadas con str_pad
                    $colMateria = str_pad(substr($nombre_materia, 0, 32), 35, " ", STR_PAD_RIGHT);
					$colP1 = str_pad($notas['p1'], 6, " ", STR_PAD_RIGHT);
					$colP2 = str_pad($notas['p2'], 6, " ", STR_PAD_RIGHT);
					$colP3 = str_pad($notas['p3'], 6, " ", STR_PAD_RIGHT);
					$colP4 = str_pad($notas['p4'], 6, " ", STR_PAD_RIGHT);
					
					// Cálculo simple del promedio final (P1 a P4)
					$promedioFinal = (floatval($notas['p1']) + floatval($notas['p2']) + floatval($notas['p3']) + floatval($notas['p4'])) / 4;
					$colFinal = str_pad(number_format($promedioFinal, 1), 6, " ", STR_PAD_RIGHT);

                    $lineFinal = $colMateria . $colP1 . $colP2 . $colP3 . $colP4 . $colFinal;
					
					// Imprimir fila con fondo alternado interactivo (opcional, aquí marcado como true)
					$pdf->Cell(0, 5, utf8_decode($lineFinal), 0, 1, 'L', true); 
					
                }
                
                // Pie de página con firma del director
                $pdf->Cell(200,58,'____________________________',0,1,'C');            
                $pdf->Cell(200,-35,'Director(a) de Grupo ',0,1,'C');          
                $pdf->Cell(200,2,$datos_estudiante['doce_nombre'].' '.$datos_estudiante['doce_apellido'],0,1,'C');  
            }
        }
    } // Fin bucle foreach de cédulas   

    // 3. ENVIAR AL NAVEGADOR: Se ejecuta al final, empaquetando todas las hojas juntas
    $Filename = 'Reporte_de_Notas_Completo.pdf'; 
    $pdf->Output('I', $Filename);  
}

?>

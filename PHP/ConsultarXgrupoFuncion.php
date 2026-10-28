<?php
require('../fpdf186/fpdf.php');
require('FPrinter.php');

// 1. CORRECCIÓN: La clase PDF debe estar afuera de la función para evitar Errores Fatales en PHP
class PDF extends FPDF {
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

function GenerarBoletinMasivo($lista_cedulas, $B_periodo) {
    global $servidor, $usuario, $contraseña, $base_datos;

    // Instanciar FPDF una sola vez afuera de todos los ciclos
    $pdf = new PDF('P', 'mm', 'Legal'); // Tamaño de papel oficio
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 15); 

    // Procesar el periodo solicitado (Asumiendo que $B_periodo puede ser un array o valor único)
    if (is_array($B_periodo)) {
        foreach ($B_periodo as $T_periodo) {
            $T = htmlspecialchars(trim($T_periodo), ENT_QUOTES, 'UTF-8');
        }
    } else {
        $T = htmlspecialchars(trim($B_periodo), ENT_QUOTES, 'UTF-8');
    }

    $boletines_generados = 0;

    // BUCLE 1: Recorrer el arreglo dinámico de estudiantes enviados
    foreach ($lista_cedulas as $cedula) {
        
        $numcedula = htmlspecialchars(trim($cedula), ENT_QUOTES, 'UTF-8');

        if (!empty($numcedula) && ($T > 0)) {
            // Abrir conexión limpia para este estudiante
            $con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos) or die(mysqli_error($con)); 
            mysqli_set_charset($con, "utf8"); 

            // Matriz para organizar materias y datos adicionales
            $boletin = array();
            $datos_estudiante = null;

            // Información del colegio
            $consede = "SELECT * FROM sede";
            $reconsede = mysqli_query($con, $consede);
            $sede = mysqli_fetch_all($reconsede);

            // BUCLE 2: Consultar la información utilizando el periodo seleccionado ($T)
            $consulta = "SELECT nota_periodo, gru_jornada, gru_nombre, DATE_FORMAT(nota_fechacorte,'%d/%m/%Y') AS fecha_corte,
                                estu_foto, estu_nombre, estu_apellido, asig_nombre, doce_nombre, doce_apellido,
                                asig_intensidad, nota_nota, nota_inasistencias, asig_logro1, asig_logro2, asig_logro3, asig_logro4, nota_observacion
                         FROM notas 
                         INNER JOIN estudiantes ON notas.nota_estu_id=estudiantes.estu_id 
                         INNER JOIN asignaturas ON notas.nota_asig_id=asignaturas.asig_id 
                         INNER JOIN docentes ON asignaturas.asig_doce_id=docentes.doce_id 
                         INNER JOIN grupos ON grupos.gru_id=notas.nota_gru_id 
                         WHERE estu_identificacion='$numcedula' AND nota_periodo='$T' 
                         ORDER BY asig_nombre";

            $resconsulta = mysqli_query($con, $consulta);
            
            if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {  
                while ($row = mysqli_fetch_assoc($resconsulta)) {
                    if (!$datos_estudiante) {
                        // Guardamos los datos demográficos básicos en la primera coincidencia
                        $datos_estudiante = $row;
                    }

                    $materia = $row['asig_nombre'];
                    
                    // Almacenamos toda la información de la fila para renderizarla en la tabla
                    $boletin[] = array(
                        'materia'       => $row['asig_nombre'],
                        'docente'       => $row['doce_apellido'] . ' ' . $row['doce_nombre'],
                        'intensidad'    => $row['asig_intensidad'],
                        'nota'          => $row['nota_nota'],
                        'inasistencias' => $row['nota_inasistencias'],
                        'logro1'         => $row['asig_logro1'],
						'logro2'         => $row['asig_logro2'],
						'logro3'         => $row['asig_logro3'],
						'logro4'         => $row['asig_logro4'],
                        'observacion'   => $row['nota_observacion']
                    );
                }
            }  
            
            mysqli_close($con); // Cerrar conexión de forma segura

            // 2. SI EL ESTUDIANTE TIENE NOTAS, GENERAMOS SU HOJA INDEPENDIENTE EN EL PDF
            if ($datos_estudiante !== null) {
                $boletines_generados++;
                $pdf->AddPage(); // Página nueva exclusiva para este alumno
                
                // Encabezados de la Institución
                $pdf->SetLineWidth(1);
                $pdf->SetFillColor(200, 220, 255);
                
                $pdf->SetFont('Arial', 'B', 12); 
                $pdf->Cell(0, 2, (isset($sede[0][1]) ? $sede[0][1] : ''), 0, 1, 'C');    
                $pdf->SetFont('Arial', '', 10); 
                $pdf->Cell(0, 7, 'NIT '.(isset($sede[0][2]) ? $sede[0][2] : ''), 0, 1, 'C'); 
                $pdf->Cell(0, 4, (isset($sede[0][3]) ? $sede[0][3] : ''), 0, 1, 'C');    
                $pdf->Cell(0, 4, (isset($sede[0][4]) ? $sede[0][4] : '').'       Tel : '.(isset($sede[0][5]) ? $sede[0][5] : ''), 0, 1, 'C');    
                $pdf->Cell(0, 4, (isset($sede[0][6]) ? $sede[0][6] : ''), 0, 1, 'C');    
                $pdf->Cell(130, 3, '________________________________', 0, 1, 'R');
                $pdf->Cell(125, 3, ' ', 0, 1, 'R');    
                $pdf->Cell(125, 3, '     INFORME DE NOTAS', 0, 1, 'R');    
                
                $pdf->Image("LogoColegio.png", 14, 10, 12);  
                $pdf->Image("LogoAlcaldia.png", 187, 10, 12);  
                
                if (!empty($datos_estudiante['estu_foto'])) {
                    $pdf->Image('../Fotos/' . $datos_estudiante['estu_foto'], 14, 34, 15);  
                }

                // Información del Estudiante
                $pdf->SetFont('Courier', 'B', 10); 
                $line_estudiante = $datos_estudiante['estu_apellido'] . " " . $datos_estudiante['estu_nombre']; 
                $pdf->Cell(102, 7, utf8_decode(str_pad($line_estudiante, 35)), 0, 1, 'R');
                
                $texto_jornada = "JORNADA DE LA " . $datos_estudiante['gru_jornada'] . "        GRADO :" . $datos_estudiante['gru_nombre'];
                $pdf->Cell(110, 7, utf8_decode($texto_jornada), 0, 1, 'R');
             
                $pdf->Cell(63, 5, "INFORME DE NOTAS ", 0, 0, 'R');
                $pdf->Cell(45, 5, ' PERIODO: ' . $T, 0, 0, 'R');    
                $pdf->Cell(53, 5, ' FECHA : ' . $datos_estudiante['fecha_corte'], 0, 1, 'R');    

                $pdf->Cell(0, 4, ' ', 0, 1, 'L'); // Salto de línea estético
                
                // Dibujar tabla de materias calificadas con Courier (Fijo para alineación de columnas)
                $pdf->SetFont('Courier', 'B', 11); 	
                $encabezado = 'MATERIA                         DOCENTE                      IH   DESEMPEÑO   I';
                $pdf->Cell(0, 5, utf8_decode($encabezado), 0, 1, 'L');   
                $pdf->SetFont('Courier', '', 10); 

                // CORRECCIÓN: Iterar sobre la matriz local '$boletin' que sí tiene los datos cargados de este alumno
                foreach ($boletin as $item) {
                    $pdf->Cell(0, 4, ' ', 0, 1, 'L'); // Espacio entre asignaturas

                    $materia = utf8_decode($item['materia']);
                    $docente = utf8_decode($item['docente']);
                    
                    $line = str_pad($materia, 35, " "); 
                    $line = $line . str_pad($docente, 33, " ");  
                    $line = $line . str_pad($item['intensidad'], 4, " ");    
                    
                    // Evaluar rendimiento académico
                    $nota = $item['nota'];
                    $rendimiento = '';
                    if ($nota >= 4.6) {
                        $rendimiento = '(Supe)';
                    } elseif ($nota >= 4.0 && $nota <= 4.5) {
                        $rendimiento = '(Alto)'; 
                    } elseif ($nota >= 3.0 && $nota <= 3.9) {
                        $rendimiento = '(Basi)'; 
                    } elseif ($nota >= 0.0 && $nota <= 2.9) {
                        $rendimiento = '(Bajo)';
                    }
                    
                    $line = $line . str_pad($nota . ' ' . $rendimiento, 13, " ");  
                    $line = $line . str_pad($item['inasistencias'], 4, " ");					 
                    
                    // Imprimir fila de la materia
                    $pdf->Cell(0, 4, $line, 0, 1, 'L', true);  
                    
                    // Imprimir Logros y Observaciones asociados
					switch ($T) {
						case 1:	$pdf->MultiCell(0, 4, utf8_decode($item['logro1']), 0, 'L');break;
						case 2:	$pdf->MultiCell(0, 4, utf8_decode($item['logro2']), 0, 'L');break;
						case 3:	$pdf->MultiCell(0, 4, utf8_decode($item['logro3']), 0, 'L');break;
						case 4:	$pdf->MultiCell(0, 4, utf8_decode($item['logro4']), 0, 'L');break;
					}					
                    if (!empty($item['observacion'])) {
                        $pdf->MultiCell(0, 4, utf8_decode('Observacion: ' . $item['observacion']), 0, 'L');  
                    }
                }
            }
        }
    }

    // Enviar el PDF acumulado al navegador si se generó al menos un informe
    if ($boletines_generados > 0) {
        $Filename = 'Reporte_de_Notas_Masivo.pdf'; 
        $pdf->Output('I', $Filename);
    } else {
        echo "<p style='color:red;'>NO EXISTEN DATOS EN EL PERIODO PARA LAS CÉDULAS SELECCIONADAS.</p>";
    }
}
?>

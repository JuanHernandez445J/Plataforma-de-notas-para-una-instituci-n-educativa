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
    
    // Capturar y sanitizar la cédula recibida
    $cedula = $_POST['Ndoc'] ?? '';
    $numcedula = htmlspecialchars($cedula, ENT_QUOTES, 'UTF-8');
    
    if (!empty($numcedula)) {
        
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
        
        // Inicializar vectores para almacenar la información consolidada
        $Materias = [];
        $t1 = []; $t2 = []; $t3 = []; $t4 = []; $t5 = [];
        $datosEstudiante = null;
        
        // Recorrer los 4 periodos de forma lógica para agrupar los datos antes de procesar el PDF
        for ($ii = 1; $ii <= 4; $ii++) {
            $Busca_periodo = $ii;
            
            $consulta = "SELECT n.nota_periodo, g.gru_jornada, g.gru_nombre, e.estu_nombre, e.estu_foto, 
                                e.estu_apellido, a.asig_nombre, n.nota_nota, d.doce_nombre, d.doce_apellido 
                         FROM notas n
                         INNER JOIN estudiantes e ON n.nota_estu_id = e.estu_id 
                         INNER JOIN asignaturas a ON n.nota_asig_id = a.asig_id 
                         INNER JOIN grupos g ON g.gru_id = n.nota_gru_id 
                         INNER JOIN docentes d ON d.doce_id = g.gru_doce_id 
                         WHERE e.estu_identificacion = '$numcedula' AND n.nota_periodo = '$Busca_periodo' 
                         ORDER BY a.asig_nombre";
            
            $resconsulta = mysqli_query($con, $consulta);
            
            if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {
                while ($fila = mysqli_fetch_assoc($resconsulta)) {
                    $materiaNombre = $fila['asig_nombre'];
                    
                    // Si la materia no ha sido registrada en nuestro listado, la agregamos
                    if (!in_array($materiaNombre, $Materias)) {
                        $Materias[] = $materiaNombre;
                        $idx = count($Materias) - 1;
                        // Inicializamos los periodos en limpio para esta materia
                        $t1[$idx] = "0.0"; $t2[$idx] = "0.0"; $t3[$idx] = "0.0"; $t4[$idx] = "0.0"; $t5[$idx] = "0.0";
                    } else {
                        $idx = array_search($materiaNombre, $Materias);
                    }
                    
                    // Guardar los datos generales del estudiante (solo se necesita una vez)
                    if (empty($datosEstudiante)) {
                        $datosEstudiante = $fila;
                    }
                    
                    // Asignar la nota al periodo correspondiente
                    switch ($Busca_periodo) {
                        case 1: $t1[$idx] = $fila['nota_nota']; break;
                        case 2: $t2[$idx] = $fila['nota_nota']; break;
                        case 3: $t3[$idx] = $fila['nota_nota']; break;
                        case 4: $t4[$idx] = $fila['nota_nota']; break;
                    }
                }
            }
        } // Fin del recorrido de periodos
        
        // Si encontramos información del estudiante, procedemos a generar el PDF (UNA SOLA VEZ)
        if (!empty($datosEstudiante)) {
            
            $pdf = new PDF('P', 'mm', 'Legal');
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
            $pdf->Cell(125, 3, ' CONCENTRADOR DE NOTAS', 0, 1, 'R');
            
            // --- IMÁGENES Y LOGOS ---
            if (file_exists("LogoColegio.png")) $pdf->Image("LogoColegio.png", 14, 10, 12);
            if (file_exists("LogoAlcaldia.png")) $pdf->Image("LogoAlcaldia.png", 187, 10, 12);
            
            $fotoRuta = 'Fotos/' . $datosEstudiante['estu_foto'];
            if (!empty($datosEstudiante['estu_foto']) && file_exists($fotoRuta)) {
                $pdf->Image($fotoRuta, 14, 34, 15);
            }
            
            // --- DATOS DEL ESTUDIANTE ---
            $pdf->SetFont('Courier', 'B', 10); 
            $nombreCompleto = $datosEstudiante['estu_apellido'] . " " . $datosEstudiante['estu_nombre'];
            $line = str_pad($nombreCompleto, 35);
            $pdf->Cell(0, 8, str_pad(utf8_decode($line), 80), 0, 1, 'R'); 
            
            $gradoJornada = "GRADO: " . $datosEstudiante['gru_nombre'] . "  JORNADA: " . $datosEstudiante['gru_jornada'];
            $line = str_pad($gradoJornada, 35);
            $pdf->Cell(0, 8, str_pad(utf8_decode($line), 80), 0, 1, 'R'); 
            
            // Cabecera de la tabla de calificaciones
            $line = utf8_decode(str_pad('ASIGNATURA                             P1     P2    P3     P4    FINAL', 90, " "));
            $pdf->Cell(0, 4, '' . $line, 0, 1, 'L');
            
            // --- LISTADO DE MATERIAS Y CALIFICACIONES ---
            $pdf->SetFont('Courier', '', 11);
            $pdf->SetFillColor(200, 220, 255);
            
            $longitud = count($Materias);
            for ($i = 0; $i < $longitud; $i++) {
                $pdf->Cell(0, 2, ' ', 0, 1, 'L'); // Pequeño espacio de separación
                
                // Formatear columnas alineadas con str_pad
                $colMateria = str_pad(substr($Materias[$i], 0, 32), 35, " ", STR_PAD_RIGHT);
                $colP1 = str_pad($t1[$i], 6, " ", STR_PAD_RIGHT);
                $colP2 = str_pad($t2[$i], 6, " ", STR_PAD_RIGHT);
                $colP3 = str_pad($t3[$i], 6, " ", STR_PAD_RIGHT);
                $colP4 = str_pad($t4[$i], 6, " ", STR_PAD_RIGHT);
                
                // Cálculo simple del promedio final (P1 a P4)
                $promedioFinal = (floatval($t1[$i]) + floatval($t2[$i]) + floatval($t3[$i]) + floatval($t4[$i])) / 4;
                $colFinal = str_pad(number_format($promedioFinal, 1), 6, " ", STR_PAD_RIGHT);
                
                $lineFinal = $colMateria . $colP1 . $colP2 . $colP3 . $colP4 . $colFinal;
                
                // Imprimir fila con fondo alternado interactivo (opcional, aquí marcado como true)
                $pdf->Cell(0, 5, utf8_decode($lineFinal), 0, 1, 'L', true); 
            }
            
            // --- SECCIÓN DE FIRMAS ---
            $pdf->Ln(25);
            $pdf->Cell(200, 5, '____________________________', 0, 1, 'C');
            $pdf->Cell(200, 5, utf8_decode('Director(a) de Grupo'), 0, 1, 'C');
            $docenteNombre = $datosEstudiante['doce_nombre'] . ' ' . $datosEstudiante['doce_apellido'];
            $pdf->Cell(200, 5, utf8_decode($docenteNombre), 0, 1, 'C'); 
            
            // Descarga o renderizado del documento
            $Filename = 'Reporte_de_Notas.pdf';
            if (file_exists($Filename)) { 
                @unlink($Filename); 
            }
            $pdf->Output('I', $Filename);
            
        } else {
            echo "<p>No se encontraron registros para la identificación suministrada.</p>";
        }
        
        mysqli_close($con);
    }
}
?>

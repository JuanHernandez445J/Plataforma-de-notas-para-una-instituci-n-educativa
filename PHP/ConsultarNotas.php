
<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudo conectar con la base de datos.'
    ]);
    exit;
}

mysqli_set_charset($con, "utf8");

$jornada = trim($_POST['Jornad'] ?? '');
$grupo = (int)($_POST['Grupo'] ?? 0);
$materia = (int)($_POST['Materia'] ?? 0);
$periodo = (int)($_POST['Periodo'] ?? 0);

if ($jornada === '' || $grupo <= 0 || $materia <= 0 || $periodo < 1 || $periodo > 4) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Selecciona una jornada, un grupo, una materia y un período válidos.'
    ]);
    mysqli_close($con);
    exit;
}

$consultaGrupo = "SELECT gru_id FROM grupos WHERE gru_id = ? AND gru_jornada = ?";
$stmtGrupo = mysqli_prepare($con, $consultaGrupo);
mysqli_stmt_bind_param($stmtGrupo, "is", $grupo, $jornada);
mysqli_stmt_execute($stmtGrupo);
$resultadoGrupo = mysqli_stmt_get_result($stmtGrupo);
$filaGrupo = mysqli_fetch_assoc($resultadoGrupo);
mysqli_stmt_close($stmtGrupo);

if (!$filaGrupo) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'El grupo no corresponde a la jornada seleccionada.'
    ]);
    mysqli_close($con);
    exit;
}

$consultaMateria = "SELECT asig_id FROM asignaturas WHERE asig_id = ? AND asig_gru_id = ?";
$stmtMateria = mysqli_prepare($con, $consultaMateria);
mysqli_stmt_bind_param($stmtMateria, "ii", $materia, $grupo);
mysqli_stmt_execute($stmtMateria);
$resultadoMateria = mysqli_stmt_get_result($stmtMateria);
$filaMateria = mysqli_fetch_assoc($resultadoMateria);
mysqli_stmt_close($stmtMateria);

if (!$filaMateria) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'La materia seleccionada no pertenece al grupo indicado.'
    ]);
    mysqli_close($con);
    exit;
}

$consulta = "SELECT
                n.nota_id,
                e.estu_identificacion,
                e.estu_nombre,
                e.estu_apellido,
                n.nota_nota,
                n.nota_inasistencias,
                n.nota_fechacorte,
                n.nota_observacion
             FROM estudiantes e
             LEFT JOIN notas n
                ON n.nota_estu_id = e.estu_id
                AND n.nota_gru_id = ?
                AND n.nota_asig_id = ?
                AND n.nota_periodo = ?
             WHERE e.estu_gru_id = ?
             ORDER BY e.estu_apellido ASC, e.estu_nombre ASC";

$stmt = mysqli_prepare($con, $consulta);

if (!$stmt) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudo preparar la consulta de notas.'
    ]);
    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmt, "iiii", $grupo, $materia, $periodo, $grupo);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudo consultar el listado de notas.'
    ]);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit;
}

$resultado = mysqli_stmt_get_result($stmt);
$notas = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $notas[] = $fila;
}

echo json_encode([
    'error' => false,
    'notas' => $notas
], JSON_UNESCAPED_UNICODE);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

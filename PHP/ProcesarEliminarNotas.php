
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
$accion = $_POST['accion'] ?? '';

if ($jornada === '' || $grupo <= 0 || $materia <= 0 || $periodo < 1 || $periodo > 4) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Selecciona una jornada, grupo, materia y período válidos.'
    ]);
    mysqli_close($con);
    exit;
}

if ($accion !== 'contar' && $accion !== 'eliminar') {
    echo json_encode([
        'error' => true,
        'mensaje' => 'La acción solicitada no es válida.'
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

if ($accion === 'contar') {
    $consulta = "SELECT COUNT(*) AS total FROM notas
                 WHERE nota_gru_id = ?
                 AND nota_asig_id = ?
                 AND nota_periodo = ?";

    $stmt = mysqli_prepare($con, $consulta);
    mysqli_stmt_bind_param($stmt, "iii", $grupo, $materia, $periodo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($resultado);

    echo json_encode([
        'error' => false,
        'total' => (int)$fila['total']
    ]);

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit;
}

$consulta = "DELETE FROM notas
             WHERE nota_gru_id = ?
             AND nota_asig_id = ?
             AND nota_periodo = ?";

$stmt = mysqli_prepare($con, $consulta);
mysqli_stmt_bind_param($stmt, "iii", $grupo, $materia, $periodo);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudieron eliminar las notas.'
    ]);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit;
}

$totalEliminadas = mysqli_stmt_affected_rows($stmt);

echo json_encode([
    'error' => false,
    'mensaje' => 'Se eliminaron ' . $totalEliminadas . ' notas correctamente.'
]);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

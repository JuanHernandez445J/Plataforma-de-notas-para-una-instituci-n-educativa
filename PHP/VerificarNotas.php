<?php
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_OFF);

require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudo conectar con la base de datos: ' . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($con, "utf8");

$jornada = $_POST['Jornad'] ?? '';
$grupo = $_POST['Grupo'] ?? '';
$materia = $_POST['Materia'] ?? '';
$periodo = $_POST['Periodo'] ?? '';

if ($jornada == '' || $grupo == '' || $materia == '' || $periodo == '') {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Debes seleccionar la jornada, el grupo, la materia y el período.'
    ]);
    exit;
}

$consulta = "SELECT COUNT(*) AS total
             FROM notas
             WHERE nota_asig_id = ?
             AND nota_periodo = ?
             AND nota_gru_id = ?";
$stmt = mysqli_prepare($con, $consulta);

$grupoConsulta = "SELECT gru_id FROM grupos WHERE gru_nombre = ? AND gru_jornada = ?";
$stmtGrupo = mysqli_prepare($con, $grupoConsulta);
mysqli_stmt_bind_param($stmtGrupo, "ss", $grupo, $jornada);
mysqli_stmt_execute($stmtGrupo);
$resultadoGrupo = mysqli_stmt_get_result($stmtGrupo);
$filaGrupo = mysqli_fetch_assoc($resultadoGrupo);

if (!$filaGrupo) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se encontró el grupo seleccionado.'
    ]);
    mysqli_stmt_close($stmtGrupo);
    mysqli_close($con);
    exit;
}

$grupoId = (int)$filaGrupo['gru_id'];
mysqli_stmt_close($stmtGrupo);

$stmt = mysqli_prepare($con, $consulta);

if (!$stmt) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error en la consulta SQL: ' . mysqli_error($con)
    ]);
    mysqli_close($con);
    exit;
}

$materia = (int)$materia;
$periodo = (int)$periodo;

mysqli_stmt_bind_param($stmt, "iii", $materia, $periodo, $grupoId);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'No se pudo ejecutar la consulta: ' . mysqli_stmt_error($stmt)
    ]);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit;
}

$resultado = mysqli_stmt_get_result($stmt);
$fila = mysqli_fetch_assoc($resultado);

echo json_encode([
    'error' => false,
    'existenNotas' => (int)$fila['total'] > 0
]);

mysqli_stmt_close($stmt);
mysqli_close($con);
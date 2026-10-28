<?php
mysqli_report(MYSQLI_REPORT_OFF);
header('Content-Type: application/json; charset=utf-8');

require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    echo json_encode(['error' => 'No se pudo conectar con la base de datos.']);
    exit;
}

mysqli_set_charset($con, "utf8");

$grupo = filter_input(INPUT_POST, 'grupo', FILTER_VALIDATE_INT);
$materia = filter_input(INPUT_POST, 'materia', FILTER_VALIDATE_INT);
$periodo = filter_input(INPUT_POST, 'periodo', FILTER_VALIDATE_INT);

if (!$grupo || !$materia || !$periodo || $periodo < 1 || $periodo > 4) {
    echo json_encode(['error' => 'Seleccione un grupo, una materia y un período válidos.']);
    mysqli_close($con);
    exit;
}

$sql = "SELECT n.nota_id, n.nota_nota, n.nota_inasistencias,
               n.nota_fechacorte, n.nota_observacion,
               e.estu_identificacion, e.estu_nombre, e.estu_apellido
        FROM notas n
        INNER JOIN estudiantes e ON n.nota_estu_id = e.estu_id
        WHERE n.nota_gru_id = ?
        AND n.nota_asig_id = ?
        AND n.nota_periodo = ?
        ORDER BY e.estu_apellido ASC, e.estu_nombre ASC";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(['error' => 'No se pudo preparar la consulta.']);
    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmt, "iii", $grupo, $materia, $periodo);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$notas = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $notas[] = $fila;
}

echo json_encode($notas, JSON_UNESCAPED_UNICODE);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

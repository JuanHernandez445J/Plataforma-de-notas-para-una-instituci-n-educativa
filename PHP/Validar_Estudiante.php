
<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode([
        "existe" => false,
        "mensaje" => "Solicitud no válida."
    ]);
    exit;
}

$identificacion = trim($_POST["identificacion"] ?? "");
$periodo = trim($_POST["periodo"] ?? "");

if ($identificacion === "" || $periodo === "" ||
    !ctype_digit($periodo) || (int)$periodo < 1 || (int)$periodo > 4) {
    echo json_encode([
        "existe" => false,
        "mensaje" => "Verifica el documento y el período académico."
    ]);
    exit;
}

$periodo = (int)$periodo;

$con = mysqli_connect("localhost", "root", "", "Boletines");

if (!$con) {
    http_response_code(500);
    echo json_encode([
        "existe" => false,
        "mensaje" => "No se pudo conectar con la base de datos."
    ]);
    exit;
}

mysqli_set_charset($con, "utf8");

$sql = "SELECT estu_id
        FROM estudiantes
        WHERE estu_identificacion = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $identificacion);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    echo json_encode([
        "existe" => false,
        "mensaje" => "La identificación no está registrada en la institución."
    ]);

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit;
}

mysqli_stmt_close($stmt);

$sqlNotas = "SELECT notas.nota_id
             FROM notas
             INNER JOIN estudiantes
                 ON notas.nota_estu_id = estudiantes.estu_id
             WHERE estudiantes.estu_identificacion = ?
             AND notas.nota_periodo = ?
             LIMIT 1";

$stmtNotas = mysqli_prepare($con, $sqlNotas);

if (!$stmtNotas) {
    http_response_code(500);
    echo json_encode([
        "existe" => false,
        "mensaje" => "Error al consultar las notas."
    ]);

    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmtNotas, "si", $identificacion, $periodo);
mysqli_stmt_execute($stmtNotas);

$resultadoNotas = mysqli_stmt_get_result($stmtNotas);

if (mysqli_num_rows($resultadoNotas) == 0) {
    echo json_encode([
        "existe" => false,
        "mensaje" => "El estudiante existe, pero no tiene notas asignadas para el período " . $periodo . "."
    ]);
} else {
    echo json_encode([
        "existe" => true,
        "mensaje" => "Estudiante y notas encontrados."
    ]);
}

mysqli_stmt_close($stmtNotas);
mysqli_close($con);
?>


<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['existe' => false]);
    exit;
}

$identificacion = trim($_POST['Ndoc'] ?? '');

if ($identificacion === '') {
    echo json_encode(['existe' => false]);
    exit;
}

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    http_response_code(500);
    echo json_encode(['existe' => false]);
    exit;
}

mysqli_set_charset($con, 'utf8mb4');

$sql = "SELECT estu_id FROM estudiantes WHERE estu_identificacion = ? LIMIT 1";
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['existe' => false]);
    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $identificacion);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

$existe = mysqli_stmt_num_rows($stmt) > 0;

echo json_encode(['existe' => $existe]);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

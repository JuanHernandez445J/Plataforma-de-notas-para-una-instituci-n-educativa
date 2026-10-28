<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'existe' => false,
        'mensaje' => 'Método no permitido.'
    ]);
    exit;
}

$grupo = trim($_POST['Grupo'] ?? '');
$jornada = strtoupper(trim($_POST['Jorna'] ?? ''));

if ($jornada === 'MANANA') {
    $jornada = 'MAÑANA';
}

if ($grupo === '' || !in_array($jornada, ['MAÑANA', 'TARDE', 'NOCHE'], true)) {
    echo json_encode([
        'existe' => false,
        'mensaje' => 'Los datos ingresados no son válidos.'
    ]);
    exit;
}

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    http_response_code(500);

    echo json_encode([
        'existe' => false,
        'mensaje' => 'No se pudo conectar con la base de datos.'
    ]);
    exit;
}

mysqli_set_charset($con, 'utf8mb4');

$sql = "SELECT gru_id
        FROM grupos
        WHERE LOWER(TRIM(gru_nombre)) = LOWER(?)
        AND UPPER(TRIM(gru_jornada)) = ?
        LIMIT 1";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    http_response_code(500);

    echo json_encode([
        'existe' => false,
        'mensaje' => 'No se pudo realizar la consulta.'
    ]);

    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $grupo, $jornada);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

$existe = mysqli_stmt_num_rows($stmt) > 0;

echo json_encode([
    'existe' => $existe,
    'mensaje' => $existe ? 'Grupo encontrado.' : 'No existe ese grupo con esa jornada.'
]);

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
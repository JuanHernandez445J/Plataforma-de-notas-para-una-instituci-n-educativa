
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';

$con = mysqli_connect(
    $servidor,
    $usuario,
    $contraseña,
    $base_datos
);

if (!$con) {
    echo json_encode([
        "existe" => false,
        "error" => "Error de conexión con la base de datos."
    ]);
    exit;
}

mysqli_set_charset($con, "utf8");

$grupo = trim($_POST['Grupo'] ?? '');
$jornada = trim($_POST['Jornad'] ?? '');

if ($grupo === '' || $jornada === '') {
    echo json_encode([
        "existe" => false,
        "mensaje" => "Debes ingresar el grupo y la jornada."
    ]);

    mysqli_close($con);
    exit;
}

$sql = "SELECT gru_id
        FROM grupos
        WHERE LOWER(TRIM(gru_nombre)) = LOWER(?)
        AND LOWER(TRIM(gru_jornada)) = LOWER(?)
        LIMIT 1";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "ss", $grupo, $jornada);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    echo json_encode([
        "existe" => true,
        "mensaje" => "El grupo existe con esa jornada."
    ]);
} else {
    echo json_encode([
        "existe" => false,
        "mensaje" => "El grupo no existe con la jornada seleccionada."
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

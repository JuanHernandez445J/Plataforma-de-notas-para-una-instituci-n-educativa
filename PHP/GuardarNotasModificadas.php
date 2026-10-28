
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

$notaId = filter_input(INPUT_POST, 'nota_id', FILTER_VALIDATE_INT);
$calificacion = isset($_POST['nota_nota']) ? trim($_POST['nota_nota']) : '';
$inasistencias = filter_input(INPUT_POST, 'nota_inasistencias', FILTER_VALIDATE_INT);
$observacion = isset($_POST['nota_observacion']) ? trim($_POST['nota_observacion']) : '';

if (!$notaId || $calificacion === '' || !is_numeric($calificacion) || $inasistencias === false || $inasistencias === null || $inasistencias < 0) {
    echo json_encode(['error' => 'Verifique la calificación y las inasistencias.']);
    mysqli_close($con);
    exit;
}

$sql = "UPDATE notas
        SET nota_nota = ?, nota_inasistencias = ?, nota_observacion = ?
        WHERE nota_id = ?";

$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(['error' => 'No se pudo preparar la actualización.']);
    mysqli_close($con);
    exit;
}

mysqli_stmt_bind_param($stmt, "sisi", $calificacion, $inasistencias, $observacion, $notaId);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) >= 0) {
        echo json_encode(['mensaje' => 'Los cambios se guardaron correctamente.']);
    } else {
        echo json_encode(['error' => 'No se pudieron guardar los cambios.']);
    }
} else {
    echo json_encode(['error' => 'Ocurrió un error al modificar la nota.']);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>

<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$asig_gru_id = $_POST["asig_gru_id"];
$asig_nombre = $_POST["asig_nombre"];
$asig_doce_id = $_POST["asig_doce_id"];
$asig_logro = $_POST["asig_logro"];
$asig_intensidad = $_POST["asig_intensidad"];

$sqlGrupo = "SELECT gru_id FROM grupos WHERE gru_id = ?";
$stmtGrupo = $conexion->prepare($sqlGrupo);
$stmtGrupo->bind_param("i", $asig_gru_id);
$stmtGrupo->execute();
$resultadoGrupo = $stmtGrupo->get_result();

if ($resultadoGrupo->num_rows == 0) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "El grupo ingresado no existe."
    ]);
    exit;
}

$sqlDocente = "SELECT doce_id FROM docentes WHERE doce_id = ?";
$stmtDocente = $conexion->prepare($sqlDocente);
$stmtDocente->bind_param("i", $asig_doce_id);
$stmtDocente->execute();
$resultadoDocente = $stmtDocente->get_result();

if ($resultadoDocente->num_rows == 0) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "El docente ingresado no existe."
    ]);
    exit;
}

$sql = "INSERT INTO asignaturas
(asig_gru_id, asig_nombre, asig_doce_id, asig_logro, asig_intensidad)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "isisi",
    $asig_gru_id,
    $asig_nombre,
    $asig_doce_id,
    $asig_logro,
    $asig_intensidad
);

if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Materia guardada correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo guardar la materia."
    ]);

}

$stmtGrupo->close();
$stmtDocente->close();
$stmt->close();
$conexion->close();

?>
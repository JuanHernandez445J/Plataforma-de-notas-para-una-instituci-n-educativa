<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$gru_jornada = $_POST["gru_jornada"];
$gru_nombre = $_POST["gru_nombre"];

$sql = "INSERT INTO grupos (gru_jornada, gru_nombre) VALUES (?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ss",
    $gru_jornada,
    $gru_nombre
);

if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Grupo guardado correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo guardar el grupo."
    ]);

}

$stmt->close();
$conexion->close();

?>
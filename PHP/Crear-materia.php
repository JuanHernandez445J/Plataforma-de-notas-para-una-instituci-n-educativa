<?php

include "conexion.php";

header("Content-Type: application/json");


$asig_gru_id = $_POST["asig_gru_id"];
$asig_nombre = $_POST["asig_nombre"];
$asig_doce_id = $_POST["asig_doce_id"];
$asig_logro = $_POST["asig_logro"];
$asig_intensidad = $_POST["asig_intensidad"];


$sql = "INSERT INTO asignaturas
        (
            asig_gru_id,
            asig_nombre,
            asig_doce_id,
            asig_logro,
            asig_intensidad
        )
        VALUES
        (
            '$asig_gru_id',
            '$asig_nombre',
            '$asig_doce_id',
            '$asig_logro',
            '$asig_intensidad'
        )";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Materia guardada correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
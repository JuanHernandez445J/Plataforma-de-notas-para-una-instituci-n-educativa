<?php

include "conexion.php";

header("Content-Type: application/json");


$asig_id = $_POST["asig_id"];
$asig_gru_id = $_POST["asig_gru_id"];
$asig_nombre = $_POST["asig_nombre"];
$asig_doce_id = $_POST["asig_doce_id"];
$asig_logro = $_POST["asig_logro"];
$asig_intensidad = $_POST["asig_intensidad"];


$sql = "UPDATE asignaturas SET

        asig_gru_id = '$asig_gru_id',
        asig_nombre = '$asig_nombre',
        asig_doce_id = '$asig_doce_id',
        asig_logro = '$asig_logro',
        asig_intensidad = '$asig_intensidad'

        WHERE asig_id = '$asig_id'";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Materia modificada correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
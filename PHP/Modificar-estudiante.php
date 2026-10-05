<?php

include "conexion.php";

header("Content-Type: application/json");

$estu_id = $_POST["estu_id"];
$estu_foto = $_POST["estu_foto"];
$estu_nombre = $_POST["estu_nombre"];
$estu_apellido = $_POST["estu_apellido"];
$estu_identificacion = $_POST["estu_identificacion"];
$estu_gru_id = $_POST["estu_gru_id"];
$estu_acud_id = $_POST["estu_acud_id"];
$estu_observaciones = $_POST["estu_observaciones"];


$sql = "UPDATE estudiantes SET

        estu_foto = '$estu_foto',
        estu_nombre = '$estu_nombre',
        estu_apellido = '$estu_apellido',
        estu_identificacion = '$estu_identificacion',
        estu_gru_id = '$estu_gru_id',
        estu_acud_id = '$estu_acud_id',
        estu_observaciones = '$estu_observaciones'

        WHERE estu_id = '$estu_id'";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
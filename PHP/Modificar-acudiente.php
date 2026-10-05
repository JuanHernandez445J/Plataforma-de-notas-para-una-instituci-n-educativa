<?php

include "conexion.php";

header("Content-Type: application/json");

$acud_id = $_POST["acud_id"];
$acud_parentesco = $_POST["acud_parentesco"];
$acud_nombre = $_POST["acud_nombre"];
$acud_apellido = $_POST["acud_apellido"];
$acud_identificacion = $_POST["acud_identificacion"];
$acud_direccion = $_POST["acud_direccion"];
$acud_telefono = $_POST["acud_telefono"];
$acud_email = $_POST["acud_email"];


$sql = "UPDATE acudientes SET

        acud_parentesco = '$acud_parentesco',
        acud_nombre = '$acud_nombre',
        acud_apellido = '$acud_apellido',
        acud_identificacion = '$acud_identificacion',
        acud_direccion = '$acud_direccion',
        acud_telefono = '$acud_telefono',
        acud_email = '$acud_email'

        WHERE acud_id = '$acud_id'";


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
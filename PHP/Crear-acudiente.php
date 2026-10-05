<?php

include "conexion.php";

header("Content-Type: application/json");

$acud_parentesco = $_POST["acud_parentesco"];
$acud_nombre = $_POST["acud_nombre"];
$acud_apellido = $_POST["acud_apellido"];
$acud_identificacion = $_POST["acud_identificacion"];
$acud_direccion = $_POST["acud_direccion"];
$acud_telefono = $_POST["acud_telefono"];
$acud_email = $_POST["acud_email"];

$sql = "INSERT INTO acudientes 
(acud_parentesco, acud_nombre, acud_apellido, acud_identificacion, acud_direccion, acud_telefono, acud_email)
VALUES 
('$acud_parentesco', '$acud_nombre', '$acud_apellido', '$acud_identificacion', '$acud_direccion', '$acud_telefono', '$acud_email')";

if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Acudiente guardado correctamente"
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}

$conexion->close();

?>
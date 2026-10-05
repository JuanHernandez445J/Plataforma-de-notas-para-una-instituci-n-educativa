<?php

include "conexion.php";

header("Content-Type: application/json");

$doce_id = $_POST["doce_id"];
$doce_foto = $_POST["doce_foto"];
$doce_nombre = $_POST["doce_nombre"];
$doce_apellido = $_POST["doce_apellido"];
$doce_identificacion = $_POST["doce_identificacion"];
$doce_direccion = $_POST["doce_direccion"];
$doce_telefono = $_POST["doce_telefono"];
$doce_email = $_POST["doce_email"];
$doce_especialidad = $_POST["doce_especialidad"];


$sql = "UPDATE docentes SET

        doce_foto = '$doce_foto',
        doce_nombre = '$doce_nombre',
        doce_apellido = '$doce_apellido',
        doce_identificacion = '$doce_identificacion',
        doce_direccion = '$doce_direccion',
        doce_telefono = '$doce_telefono',
        doce_email = '$doce_email',
        doce_especialidad = '$doce_especialidad'

        WHERE doce_id = '$doce_id'";


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
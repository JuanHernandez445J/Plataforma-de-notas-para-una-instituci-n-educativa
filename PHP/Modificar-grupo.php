<?php

include "conexion.php";

header("Content-Type: application/json");


$gru_id = $_POST["gru_id"];

$gru_jornada = $_POST["gru_jornada"];

$gru_nombre = $_POST["gru_nombre"];


$sql = "UPDATE grupos SET

        gru_jornada = '$gru_jornada',

        gru_nombre = '$gru_nombre'

        WHERE gru_id = '$gru_id'";


if ($conexion->query($sql)) {

    echo json_encode([

        "exito" => true,

        "mensaje" => "Grupo modificado correctamente."

    ]);

} else {

    echo json_encode([

        "exito" => false,

        "mensaje" => $conexion->error

    ]);

}


$conexion->close();

?>
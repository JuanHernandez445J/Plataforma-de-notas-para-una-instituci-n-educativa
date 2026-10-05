<?php

include "conexion.php";

header("Content-Type: application/json");


$gru_jornada = $_POST["gru_jornada"];
$gru_nombre = $_POST["gru_nombre"];


$sql = "INSERT INTO grupos
        (
            gru_jornada,
            gru_nombre
        )
        VALUES
        (
            '$gru_jornada',
            '$gru_nombre'
        )";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Grupo guardado correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
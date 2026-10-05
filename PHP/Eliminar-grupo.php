<?php

include "conexion.php";

header("Content-Type: application/json");


$gru_id = $_POST["gru_id"];


$sql = "DELETE FROM grupos WHERE gru_id = '$gru_id'";


if ($conexion->query($sql)) {

    if ($conexion->affected_rows > 0) {

        echo json_encode([
            "exito" => true
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No existe un grupo con ese ID."
        ]);

    }

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
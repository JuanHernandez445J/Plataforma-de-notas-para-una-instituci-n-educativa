<?php

include "conexion.php";

header("Content-Type: application/json");


$estu_id = $_POST["estu_id"];


$sql = "DELETE FROM estudiantes
        WHERE estu_id = '$estu_id'";


if ($conexion->query($sql)) {

    if ($conexion->affected_rows > 0) {

        echo json_encode([
            "exito" => true
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No existe un estudiante con ese ID."
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
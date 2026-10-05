<?php

include "conexion.php";

header("Content-Type: application/json");

$acud_id = $_POST["acud_id"];

$sql = "DELETE FROM acudientes WHERE acud_id = '$acud_id'";

if ($conexion->query($sql)) {

    if ($conexion->affected_rows > 0) {

        echo json_encode([
            "exito" => true
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No existe un acudiente con ese ID."
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
<?php

include "conexion.php";

header("Content-Type: application/json");

$doce_id = $_POST["doce_id"];

$sql = "DELETE FROM docentes WHERE doce_id = '$doce_id'";

if ($conexion->query($sql)) {

    if ($conexion->affected_rows > 0) {

        echo json_encode([
            "exito" => true
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No existe un docente con ese ID."
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
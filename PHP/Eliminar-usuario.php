<?php

include "conexion.php";

header("Content-Type: application/json");


$usua_id = $_POST["usua_id"];


$sql = "DELETE FROM usuarios WHERE usua_id = '$usua_id'";


if ($conexion->query($sql)) {

    if ($conexion->affected_rows > 0) {

        echo json_encode([
            "exito" => true
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No existe un usuario con ese ID."
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
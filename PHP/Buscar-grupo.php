<?php

include "conexion.php";

header("Content-Type: application/json");


$gru_id = $_GET["id"];


$sql = "SELECT * FROM grupos WHERE gru_id = '$gru_id'";


$resultado = $conexion->query($sql);


if ($resultado->num_rows > 0) {

    $grupo = $resultado->fetch_assoc();

    $grupo["exito"] = true;

    echo json_encode($grupo);

} else {

    echo json_encode([
        "exito" => false
    ]);

}


$conexion->close();

?>
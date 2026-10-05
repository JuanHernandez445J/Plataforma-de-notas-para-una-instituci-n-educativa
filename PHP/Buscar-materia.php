<?php

include "conexion.php";

header("Content-Type: application/json");

$asig_id = $_GET["id"];

$sql = "SELECT * FROM asignaturas WHERE asig_id = '$asig_id'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    $materia = $resultado->fetch_assoc();

    $materia["exito"] = true;

    echo json_encode($materia);

} else {

    echo json_encode([
        "exito" => false
    ]);

}

$conexion->close();

?>
<?php

include "conexion.php";

header("Content-Type: application/json");

$doce_id = $_GET["id"];

$sql = "SELECT * FROM docentes WHERE doce_id = '$doce_id'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    $docente = $resultado->fetch_assoc();

    $docente["exito"] = true;

    echo json_encode($docente);

} else {

    echo json_encode([
        "exito" => false
    ]);

}

$conexion->close();

?>
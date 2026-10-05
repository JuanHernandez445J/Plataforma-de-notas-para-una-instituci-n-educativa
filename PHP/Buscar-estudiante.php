<?php

include "conexion.php";

header("Content-Type: application/json");

$estu_id = $_GET["id"];

$sql = "SELECT * FROM estudiantes WHERE estu_id = '$estu_id'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    $estudiante = $resultado->fetch_assoc();

    $estudiante["exito"] = true;

    echo json_encode($estudiante);

} else {

    echo json_encode([
        "exito" => false
    ]);

}

$conexion->close();

?>
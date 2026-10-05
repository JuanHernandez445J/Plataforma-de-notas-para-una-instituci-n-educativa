<?php

include "conexion.php";

header("Content-Type: application/json");

$acud_id = $_GET["id"];

$sql = "SELECT * FROM acudientes WHERE acud_id = '$acud_id'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    $acudiente = $resultado->fetch_assoc();

    $acudiente["exito"] = true;

    echo json_encode($acudiente);

} else {

    echo json_encode([
        "exito" => false
    ]);

}

$conexion->close();

?>

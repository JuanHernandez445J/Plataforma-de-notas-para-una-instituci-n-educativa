<?php

include "conexion.php";

header("Content-Type: application/json");


$usua_id = $_GET["id"];


$sql = "SELECT * FROM usuarios WHERE usua_id = '$usua_id'";


$resultado = $conexion->query($sql);


if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    $usuario["exito"] = true;

    echo json_encode($usuario);

} else {

    echo json_encode([
        "exito" => false
    ]);

}


$conexion->close();

?>
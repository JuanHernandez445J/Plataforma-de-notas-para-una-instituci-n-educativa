<?php

include "conexion.php";

header("Content-Type: application/json");


$usua_activo = $_POST["usua_activo"];

$usua_usuario = $_POST["usua_usuario"];

$usua_clave = $_POST["usua_clave"];

$usua_rol = $_POST["usua_rol"];


$sql = "INSERT INTO usuarios
        (
            usua_activo,
            usua_usuario,
            usua_clave,
            usua_rol
        )
        VALUES
        (
            '$usua_activo',
            '$usua_usuario',
            '$usua_clave',
            '$usua_rol'
        )";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Usuario guardado correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
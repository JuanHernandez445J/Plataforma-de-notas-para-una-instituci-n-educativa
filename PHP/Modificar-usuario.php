<?php

include "conexion.php";

header("Content-Type: application/json");


$usua_id = $_POST["usua_id"];

$usua_activo = $_POST["usua_activo"];

$usua_usuario = $_POST["usua_usuario"];

$usua_clave = $_POST["usua_clave"];

$usua_rol = $_POST["usua_rol"];


$sql = "UPDATE usuarios SET

        usua_activo = '$usua_activo',

        usua_usuario = '$usua_usuario',

        usua_clave = '$usua_clave',

        usua_rol = '$usua_rol'

        WHERE usua_id = '$usua_id'";


if ($conexion->query($sql)) {

    echo json_encode([

        "exito" => true,

        "mensaje" => "Usuario modificado correctamente."

    ]);

} else {

    echo json_encode([

        "exito" => false,

        "mensaje" => $conexion->error

    ]);

}


$conexion->close();

?>
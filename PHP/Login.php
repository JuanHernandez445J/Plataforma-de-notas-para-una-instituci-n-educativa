<?php
include "conexion.php";

header("Content-Type: application/json");

$usua_usuario = $_POST["usua_usuario"];
$usua_clave = $_POST["usua_clave"];

$sql = "SELECT * FROM usuarios
        WHERE usua_usuario = '$usua_usuario'
        AND usua_clave = '$usua_clave'
        AND usua_activo = 1";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    echo json_encode([
        "exito" => true,
        "rol" => $usuario["usua_rol"],
        "mensaje" => "Inicio de sesión correcto."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Usuario o contraseña incorrectos."
    ]);

}

$conexion->close();
?>
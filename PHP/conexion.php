<?php

$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "boletines";

$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>

<?php

include "conexion.php";

header("Content-Type: application/json");


$sql = "SELECT * FROM usuarios";

$resultado = $conexion->query($sql);


$usuarios = [];


while ($fila = $resultado->fetch_assoc()) {

    $usuarios[] = $fila;

}


echo json_encode($usuarios);


$conexion->close();

?>
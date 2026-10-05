<?php

include "conexion.php";

header("Content-Type: application/json");


$sql = "SELECT * FROM grupos";

$resultado = $conexion->query($sql);


$grupos = [];


while ($fila = $resultado->fetch_assoc()) {

    $grupos[] = $fila;

}


echo json_encode($grupos);


$conexion->close();

?>
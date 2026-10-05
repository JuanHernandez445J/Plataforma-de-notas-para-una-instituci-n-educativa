<?php

include "conexion.php";

header("Content-Type: application/json");


$sql = "SELECT * FROM asignaturas";

$resultado = $conexion->query($sql);

$materias = [];


while ($fila = $resultado->fetch_assoc()) {

    $materias[] = $fila;

}


echo json_encode($materias);


$conexion->close();

?>
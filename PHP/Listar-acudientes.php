<?php

include "conexion.php";

$sql = "SELECT * FROM acudientes";

$resultado = $conexion->query($sql);

$acudientes = [];

while ($fila = $resultado->fetch_assoc()) {
    $acudientes[] = $fila;
}

header("Content-Type: application/json");

echo json_encode($acudientes);

$conexion->close();

?>
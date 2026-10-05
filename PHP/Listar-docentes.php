<?php

include "conexion.php";

$sql = "SELECT * FROM docentes";

$resultado = $conexion->query($sql);

$docentes = [];

while ($fila = $resultado->fetch_assoc()) {
    $docentes[] = $fila;
}

header("Content-Type: application/json");

echo json_encode($docentes);

$conexion->close();

?>
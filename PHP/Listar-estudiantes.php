<?php

include "conexion.php";

$sql = "SELECT * FROM estudiantes";

$resultado = $conexion->query($sql);

$estudiantes = [];

while ($fila = $resultado->fetch_assoc()) {
    $estudiantes[] = $fila;
}

echo json_encode($estudiantes);

$conexion->close();

?>
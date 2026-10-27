<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT * FROM estudiantes";

$resultado = $conexion->query($sql);

$estudiantes = [];

while ($fila = $resultado->fetch_assoc()) {

    $fila["estu_foto_url"] = "../Fotos/" . basename($fila["estu_foto"]);

    $estudiantes[] = $fila;
}

echo json_encode($estudiantes);

$conexion->close();

?>
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT * FROM docentes";

$resultado = $conexion->query($sql);

$docentes = [];

while ($fila = $resultado->fetch_assoc()) {

    $fila["doce_foto_url"] = "../Fotos/" . basename($fila["doce_foto"]);


    $docentes[] = $fila;
}

header("Content-Type: application/json");

echo json_encode($docentes);

$conexion->close();

?>
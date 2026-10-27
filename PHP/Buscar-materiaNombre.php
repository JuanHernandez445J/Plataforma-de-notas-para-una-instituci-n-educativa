<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["nombre"]) || trim($_GET["nombre"]) === "") {
    echo json_encode([]);
    exit;
}

$nombre = "%" . trim($_GET["nombre"]) . "%";

$sql = "SELECT * FROM asignaturas WHERE asig_nombre LIKE ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();

$resultado = $stmt->get_result();

$asignaturas = [];

while ($fila = $resultado->fetch_assoc()) {
    $asignaturas[] = $fila;
}

echo json_encode($asignaturas);

$stmt->close();
$conexion->close();
?>
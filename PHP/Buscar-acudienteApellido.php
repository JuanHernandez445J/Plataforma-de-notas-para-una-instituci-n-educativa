<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["apellido"]) || trim($_GET["apellido"]) === "") {
    echo json_encode([]);
    exit;
}

$apellido = "%" . trim($_GET["apellido"]) . "%";

$sql = "SELECT * FROM acudientes WHERE acud_apellido LIKE ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $apellido);
$stmt->execute();

$resultado = $stmt->get_result();

$acudientes = [];

while ($fila = $resultado->fetch_assoc()) {
    $acudientes[] = $fila;
}

echo json_encode($acudientes);

$stmt->close();
$conexion->close();
?>
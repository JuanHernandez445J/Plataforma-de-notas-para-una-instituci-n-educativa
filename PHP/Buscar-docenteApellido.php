<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["apellido"]) || trim($_GET["apellido"]) === "") {
    echo json_encode([]);
    exit;
}

$apellido = "%" . trim($_GET["apellido"]) . "%";

$sql = "SELECT * FROM docentes WHERE doce_apellido LIKE ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $apellido);
$stmt->execute();

$resultado = $stmt->get_result();

$docentes = [];

while ($fila = $resultado->fetch_assoc()) {
    $docentes[] = $fila;
}

echo json_encode($docentes);

$stmt->close();
$conexion->close();
?>
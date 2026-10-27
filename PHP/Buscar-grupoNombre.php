<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["nombre"]) || trim($_GET["nombre"]) === "") {
    echo json_encode([]);
    exit;
}

$nombre = "%" . trim($_GET["nombre"]) . "%";

$sql = "SELECT * FROM grupos WHERE gru_nombre LIKE ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();

$resultado = $stmt->get_result();

$grupos = [];

while ($fila = $resultado->fetch_assoc()) {
    $grupos[] = $fila;
}

echo json_encode($grupos);

$stmt->close();
$conexion->close();
?>
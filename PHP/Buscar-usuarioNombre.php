
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["nombre"]) || trim($_GET["nombre"]) === "") {
    echo json_encode([]);
    exit;
}

$nombre = "%" . trim($_GET["nombre"]) . "%";

$sql = "SELECT * FROM usuarios WHERE usua_usuario LIKE ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "error" => true,
        "mensaje" => "Error al preparar la búsqueda."
    ]);
    exit;
}

$stmt->bind_param("s", $nombre);
$stmt->execute();

$resultado = $stmt->get_result();

$usuarios = [];

while ($fila = $resultado->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);

$stmt->close();
$conexion->close();

?>

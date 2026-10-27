<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["id"]) || trim($_GET["id"]) === "") {
    echo json_encode([
        "exito" => false
    ]);
    exit;
}

$doce_id = trim($_GET["id"]);

$sql = "SELECT * FROM docentes WHERE doce_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $doce_id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $docente = $resultado->fetch_assoc();
    $docente["exito"] = true;

    echo json_encode($docente);

} else {

    echo json_encode([
        "exito" => false
    ]);

}

$stmt->close();
$conexion->close();

?>
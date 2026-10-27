<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$estu_nombre = $_POST["estu_nombre"];
$estu_apellido = $_POST["estu_apellido"];
$estu_identificacion = $_POST["estu_identificacion"];
$estu_gru_id = $_POST["estu_gru_id"];
$estu_acud_id = $_POST["estu_acud_id"];
$estu_observaciones = $_POST["estu_observaciones"];

$estu_foto = "";

if (isset($_FILES["estu_foto"]) && $_FILES["estu_foto"]["error"] == 0) {

    $nombreFoto = basename($_FILES["estu_foto"]["name"]);
    $extension = strtolower(pathinfo($nombreFoto, PATHINFO_EXTENSION));

    if (!in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "La foto debe ser JPG, JPEG, PNG o WEBP."
        ]);
        exit;
    }

    $estu_foto = uniqid() . "." . $extension;

    $rutaDestino = "../Fotos/" . $estu_foto;

    if (!move_uploaded_file($_FILES["estu_foto"]["tmp_name"], $rutaDestino)) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "No se pudo subir la foto."
        ]);
        exit;
    }

} else {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes seleccionar una foto para el estudiante."
    ]);
    exit;
}

$sql = "INSERT INTO estudiantes
(estu_foto, estu_nombre, estu_apellido, estu_identificacion,
 estu_gru_id, estu_acud_id, estu_observaciones)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "sssssss",
    $estu_foto,
    $estu_nombre,
    $estu_apellido,
    $estu_identificacion,
    $estu_gru_id,
    $estu_acud_id,
    $estu_observaciones
);

if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Estudiante guardado correctamente"
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo guardar el estudiante."
    ]);
}

$stmt->close();
$conexion->close();

?>
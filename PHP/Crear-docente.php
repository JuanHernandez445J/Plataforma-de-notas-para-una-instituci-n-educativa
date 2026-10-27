<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

$doce_nombre = $_POST["doce_nombre"];
$doce_apellido = $_POST["doce_apellido"];
$doce_identificacion = $_POST["doce_identificacion"];
$doce_direccion = $_POST["doce_direccion"];
$doce_telefono = $_POST["doce_telefono"];
$doce_email = $_POST["doce_email"];
$doce_especialidad = $_POST["doce_especialidad"];

$doce_foto = "";

if (isset($_FILES["doce_foto"]) && $_FILES["doce_foto"]["error"] == 0) {

    $nombreFoto = basename($_FILES["doce_foto"]["name"]);
    $extension = strtolower(pathinfo($nombreFoto, PATHINFO_EXTENSION));

    if (!in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "La foto debe ser JPG, JPEG, PNG o WEBP."
        ]);
        exit;
    }

    $doce_foto = uniqid() . "." . $extension;

    $rutaDestino = "../Fotos/" . $doce_foto;

    if (!move_uploaded_file($_FILES["doce_foto"]["tmp_name"], $rutaDestino)) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "No se pudo subir la foto."
        ]);
        exit;
    }

} else {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes seleccionar una foto para el docente."
    ]);
    exit;
}

$sql = "INSERT INTO docentes
(doce_foto, doce_nombre, doce_apellido, doce_identificacion,
 doce_direccion, doce_telefono, doce_email, doce_especialidad)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ssssssss",
    $doce_foto,
    $doce_nombre,
    $doce_apellido,
    $doce_identificacion,
    $doce_direccion,
    $doce_telefono,
    $doce_email,
    $doce_especialidad
);

if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Docente guardado correctamente"
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo guardar el docente."
    ]);
}

$stmt->close();
$conexion->close();

?>
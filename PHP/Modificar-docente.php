<?php

include "conexion.php";

header("Content-Type: application/json");

$doce_id = $_POST["doce_id"];
$doce_nombre = $_POST["doce_nombre"];
$doce_apellido = $_POST["doce_apellido"];
$doce_identificacion = $_POST["doce_identificacion"];
$doce_direccion = $_POST["doce_direccion"];
$doce_telefono = $_POST["doce_telefono"];
$doce_email = $_POST["doce_email"];
$doce_especialidad = $_POST["doce_especialidad"];

$sqlFoto = "SELECT doce_foto FROM docentes WHERE doce_id = '$doce_id'";
$resultadoFoto = $conexion->query($sqlFoto);
$docente = $resultadoFoto->fetch_assoc();

$doce_foto = $docente["doce_foto"];

if (isset($_FILES["doce_foto"]) && $_FILES["doce_foto"]["error"] === 0) {

    $tipo = mime_content_type($_FILES["doce_foto"]["tmp_name"]);

    if (!in_array($tipo, ["image/jpeg"])) {
        echo json_encode([
            "exito" => false,
            "mensaje" => "Seleccione una imagen JPG."
        ]);
        exit;
    }

    $extension = [
        "image/jpeg" => "jpg"
    ];

    $nombreFoto = "docente_" . $doce_id . "_" . time() . "." . $extension[$tipo];

    $carpeta = "../Fotos/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    if (move_uploaded_file($_FILES["doce_foto"]["tmp_name"], $carpeta . $nombreFoto)) {
        $doce_foto = $nombreFoto;
    } else {
        echo json_encode([
            "exito" => false,
            "mensaje" => "No se pudo guardar la fotografía."
        ]);
        exit;
    }
}

$sql = "UPDATE docentes SET

        doce_foto = '$doce_foto',
        doce_nombre = '$doce_nombre',
        doce_apellido = '$doce_apellido',
        doce_identificacion = '$doce_identificacion',
        doce_direccion = '$doce_direccion',
        doce_telefono = '$doce_telefono',
        doce_email = '$doce_email',
        doce_especialidad = '$doce_especialidad'

        WHERE doce_id = '$doce_id'";


if ($conexion->query($sql)) {

    echo json_encode([
        "exito" => true
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => $conexion->error
    ]);

}


$conexion->close();

?>
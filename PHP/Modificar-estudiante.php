<?php

include "conexion.php";

header("Content-Type: application/json");

$estu_id = $_POST["estu_id"];
$estu_nombre = $_POST["estu_nombre"];
$estu_apellido = $_POST["estu_apellido"];
$estu_identificacion = $_POST["estu_identificacion"];
$estu_gru_id = $_POST["estu_gru_id"];
$estu_acud_id = $_POST["estu_acud_id"];
$estu_observaciones = $_POST["estu_observaciones"];

$sqlFoto = "SELECT estu_foto FROM estudiantes WHERE estu_id = '$estu_id'";
$resultadoFoto = $conexion->query($sqlFoto);
$estudiante = $resultadoFoto->fetch_assoc();

$estu_foto = $estudiante["estu_foto"];

if (isset($_FILES["estu_foto"]) && $_FILES["estu_foto"]["error"] === 0) {

    $tipo = mime_content_type($_FILES["estu_foto"]["tmp_name"]);

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

    $nombreFoto = "estudiante_" . $estu_id . "_" . time() . "." . $extension[$tipo];

    $carpeta = "../Fotos/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    if (move_uploaded_file($_FILES["estu_foto"]["tmp_name"], $carpeta . $nombreFoto)) {
        $estu_foto = $nombreFoto;
    } else {
        echo json_encode([
            "exito" => false,
            "mensaje" => "No se pudo guardar la fotografía."
        ]);
        exit;
    }
}

$sql = "UPDATE estudiantes SET
        estu_foto = '$estu_foto',
        estu_nombre = '$estu_nombre',
        estu_apellido = '$estu_apellido',
        estu_identificacion = '$estu_identificacion',
        estu_gru_id = '$estu_gru_id',
        estu_acud_id = '$estu_acud_id',
        estu_observaciones = '$estu_observaciones'
        WHERE estu_id = '$estu_id'";

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
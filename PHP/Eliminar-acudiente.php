
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["acud_id"]) || trim($_POST["acud_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID del acudiente."
    ]);
    exit;
}

$acud_id = trim($_POST["acud_id"]);

$sql = "DELETE FROM acudientes WHERE acud_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $acud_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Acudiente eliminado correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe un acudiente con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar este acudiente porque tiene estudiantes u otros registros asociados."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar el acudiente."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

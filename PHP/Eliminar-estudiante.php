
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["estu_id"]) || trim($_POST["estu_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID del estudiante."
    ]);
    exit;
}

$estu_id = trim($_POST["estu_id"]);

$sql = "DELETE FROM estudiantes WHERE estu_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $estu_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Estudiante eliminado correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe un estudiante con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar este estudiante porque tiene registros relacionados, como notas u otros datos académicos."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar el estudiante."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

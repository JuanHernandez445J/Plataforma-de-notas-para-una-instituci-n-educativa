
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["asig_id"]) || trim($_POST["asig_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID de la materia."
    ]);
    exit;
}

$asig_id = trim($_POST["asig_id"]);

$sql = "DELETE FROM asignaturas WHERE asig_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $asig_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Materia eliminada correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe una materia con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar esta materia porque tiene notas u otros registros asociados."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar la materia."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

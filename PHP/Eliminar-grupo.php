
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["gru_id"]) || trim($_POST["gru_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID del grupo."
    ]);
    exit;
}

$gru_id = trim($_POST["gru_id"]);

$sql = "DELETE FROM grupos WHERE gru_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $gru_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Grupo eliminado correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe un grupo con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar este grupo porque tiene estudiantes, asignaturas u otros registros asociados."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar el grupo."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

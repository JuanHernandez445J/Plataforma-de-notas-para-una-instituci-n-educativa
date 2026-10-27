
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["doce_id"]) || trim($_POST["doce_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID del docente."
    ]);
    exit;
}

$doce_id = trim($_POST["doce_id"]);

$sql = "DELETE FROM docentes WHERE doce_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $doce_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Docente eliminado correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe un docente con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar este docente porque tiene asignaturas asociadas. Primero debes reasignarlas a otro docente o eliminar esas asignaturas."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar el docente."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

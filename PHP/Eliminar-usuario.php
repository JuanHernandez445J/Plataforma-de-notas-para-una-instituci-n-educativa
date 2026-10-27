
<?php

include "conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST["usua_id"]) || trim($_POST["usua_id"]) === "") {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Ingrese el ID del usuario."
    ]);
    exit;
}

$usua_id = trim($_POST["usua_id"]);

$sql = "DELETE FROM usuarios WHERE usua_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usua_id);

try {

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "exito" => true,
                "mensaje" => "Usuario eliminado correctamente."
            ]);

        } else {

            echo json_encode([
                "exito" => false,
                "mensaje" => "No existe un usuario con ese ID."
            ]);

        }

    }

} catch (mysqli_sql_exception $error) {

    if ($error->getCode() == 1451) {

        echo json_encode([
            "exito" => false,
            "mensaje" => "No se puede eliminar este usuario porque tiene registros asociados."
        ]);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Ocurrió un error al eliminar el usuario."
        ]);

    }

}

$stmt->close();
$conexion->close();

?>

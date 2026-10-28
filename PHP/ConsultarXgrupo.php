
<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/ConsultarXgrupoFuncion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $grup = trim($_POST['Grupo'] ?? '');
    $T = trim($_POST['Ciclo'] ?? '');
    $Bjornada = trim($_POST['Jornad'] ?? '');

    if ($grup === '' || $T === '' || $Bjornada === '' ||
        !ctype_digit($T) || (int)$T < 1 || (int)$T > 4) {
        die("Debes seleccionar un grupo, una jornada y un período válido.");
    }

    $Busca_periodo = (int)$T;

    $con = mysqli_connect(
        $servidor,
        $usuario,
        $contraseña,
        $base_datos
    );

    if (!$con) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    mysqli_set_charset($con, "utf8");

    $consulta = "SELECT estudiantes.estu_identificacion
                 FROM estudiantes
                 INNER JOIN grupos
                    ON estudiantes.estu_gru_id = grupos.gru_id
                 WHERE grupos.gru_nombre = ?
                   AND grupos.gru_jornada = ?
                 ORDER BY estudiantes.estu_apellido";

    $stmt = mysqli_prepare($con, $consulta);

    if (!$stmt) {
        die("Error al preparar la consulta: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "ss", $grup, $Bjornada);
    mysqli_stmt_execute($stmt);

    $resconsulta = mysqli_stmt_get_result($stmt);

    if ($resconsulta && mysqli_num_rows($resconsulta) > 0) {

        $estudiantes_a_procesar = [];

        while ($fila = mysqli_fetch_assoc($resconsulta)) {
            $estudiantes_a_procesar[] = $fila['estu_identificacion'];
        }

        $Bus_periodo = [$Busca_periodo];

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        GenerarBoletinMasivo(
            $estudiantes_a_procesar,
            $Bus_periodo
        );

    } else {
        echo "<p style='color:red;'>No existen estudiantes para ese grupo y jornada.</p>";

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }

} else {
    echo "Debes enviar el formulario para consultar los boletines.";
}
?>

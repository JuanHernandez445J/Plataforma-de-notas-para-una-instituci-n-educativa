
<?php
require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    die('Error de conexión: ' . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

$consultaGrupos = "SELECT gru_id, gru_nombre, gru_jornada FROM grupos ORDER BY gru_id ASC";
$resultadoGrupos = mysqli_query($con, $consultaGrupos);
$grupos = [];

while ($fila = mysqli_fetch_assoc($resultadoGrupos)) {
    $grupos[] = $fila;
}

$consultaMaterias = "SELECT asig_id, asig_nombre, asig_gru_id FROM asignaturas ORDER BY asig_id ASC";
$resultadoMaterias = mysqli_query($con, $consultaMaterias);
$materias = [];

while ($fila = mysqli_fetch_assoc($resultadoMaterias)) {
    $materias[] = $fila;
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Notas</title>
    <link rel="stylesheet" href="../CSS/EliminarNotas.css">
</head>
<body>

    <h1>Eliminar Notas Existentes</h1>

    <form id="formularioEliminar">
        <label for="Jornad">Jornada:</label>
        <select id="Jornad" name="Jornad" required>
            <option value="">Seleccione una jornada</option>
            <option value="Mañana">Mañana</option>
            <option value="Tarde">Tarde</option>
            <option value="Noche">Noche</option>
        </select>

        <label for="Grupo">Grupo:</label>
        <select id="Grupo" name="Grupo" required disabled>
            <option value="">Seleccione primero la jornada</option>
        </select>

        <label for="Materia">Materia:</label>
        <select id="Materia" name="Materia" required disabled>
            <option value="">Seleccione primero el grupo</option>
        </select>

        <label for="Periodo">Período académico:</label>
        <select id="Periodo" name="Periodo" required>
            <option value="">Seleccione un período</option>
            <option value="1">Primer período</option>
            <option value="2">Segundo período</option>
            <option value="3">Tercer período</option>
            <option value="4">Cuarto período</option>
        </select>

        <button type="submit">Consultar y eliminar notas</button>

        <a href="../Modulos/Home_docente.html">Volver</a>
    </form>

    <script>
        let matrizGrupos = <?= json_encode($grupos, JSON_UNESCAPED_UNICODE) ?>;
        let matrizMaterias = <?= json_encode($materias, JSON_UNESCAPED_UNICODE) ?>;
    </script>

    <script src="../SweetAlert2/dist/sweetalert2.all.min.js"></script>
    <script src="../JS/EliminarNotas.js"></script>

</body>
</html>


<?php
mysqli_report(MYSQLI_REPORT_OFF);
require('conexion.php');

$con = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);

if (!$con) {
    die('No se pudo conectar con la base de datos.');
}

mysqli_set_charset($con, "utf8");

$grupos = [];
$materias = [];

$resultadoGrupos = mysqli_query($con, "SELECT gru_id, gru_nombre, gru_jornada FROM grupos ORDER BY gru_id ASC");

if ($resultadoGrupos) {
    while ($fila = mysqli_fetch_assoc($resultadoGrupos)) {
        $grupos[] = $fila;
    }
}

$resultadoMaterias = mysqli_query($con, "SELECT asig_id, asig_nombre, asig_gru_id FROM asignaturas ORDER BY asig_nombre ASC");

if ($resultadoMaterias) {
    while ($fila = mysqli_fetch_assoc($resultadoMaterias)) {
        $materias[] = $fila;
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Notas</title>
    <link rel="stylesheet" href="../CSS/ListarNotas.css">
</head>
<body>

    <main class="contenedor">
        <h1>Listado de Notas</h1>
        <p class="descripcion">Consulta las notas de los estudiantes por grupo, materia y período académico.</p>

        <form id="formularioNotas">
            <div class="campo">
                <label for="Jornad">Jornada:</label>
                <select id="Jornad" name="Jornad" required>
                    <option value="">Seleccione una jornada</option>
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noche">Noche</option>
                </select>
            </div>

            <div class="campo">
                <label for="Grupo">Grupo:</label>
                <select id="Grupo" name="Grupo" required disabled>
                    <option value="">Seleccione primero la jornada</option>
                </select>
            </div>

            <div class="campo">
                <label for="Materia">Materia:</label>
                <select id="Materia" name="Materia" required disabled>
                    <option value="">Seleccione primero el grupo</option>
                </select>
            </div>

            <div class="campo">
                <label for="Periodo">Período académico:</label>
                <select id="Periodo" name="Periodo" required>
                    <option value="">Seleccione un período</option>
                    <option value="1">Primer período</option>
                    <option value="2">Segundo período</option>
                    <option value="3">Tercer período</option>
                    <option value="4">Cuarto período</option>
                </select>
            </div>

            <div class="botones">
                <button type="submit">Consultar notas</button>
                <button type="reset" id="limpiar" class="secundario">Limpiar</button>
            </div>
        </form>

        <section id="seccionResultados" hidden>
            <div class="busqueda">
                <label for="buscarEstudiante">Buscar estudiante:</label>
                <input type="text" id="buscarEstudiante" placeholder="Escribe el nombre o la identificación">
            </div>

            <p id="totalResultados"></p>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID nota</th>
                            <th>Identificación</th>
                            <th>Estudiante</th>
                            <th>Calificación</th>
                            <th>Inasistencias</th>
                            <th>Fecha de corte</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody id="tablaNotas"></tbody>
                </table>
            </div>
        </section>

        <a class="volver" href="../Modulos/Home_docente.html">Volver al panel docente</a>
    </main>

    <script>
        let matrizGrupos = <?= json_encode($grupos, JSON_UNESCAPED_UNICODE) ?>;
        let matrizMaterias = <?= json_encode($materias, JSON_UNESCAPED_UNICODE) ?>;
    </script>

    <script src="../SweetAlert2/dist/sweetalert2.all.min.js"></script>
    <script src="../JS/ListarNotas.js"></script>
</body>
</html>

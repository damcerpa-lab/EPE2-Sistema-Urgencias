
<?php
require_once "conexion.php";

$pacientes = $conexion->query("SELECT * FROM pacientes ORDER BY nombre");
$medicos = $conexion->query("SELECT * FROM medicos ORDER BY nombre");
$especialidades = $conexion->query("SELECT MIN(id_especialidad) AS id, nombre_especialidad FROM especialidades GROUP BY nombre_especialidad");
$actividades = $conexion->query("SELECT * FROM actividades ORDER BY nombre_actividad");
$destinos = $conexion->query("SELECT MIN(id_destino) AS id, nombre_destino FROM destinos GROUP BY nombre_destino");

$sql = "SELECT a.*, 
        CONCAT(p.nombre, ' ', p.apellido) AS paciente,
        CONCAT(m.nombre, ' ', m.apellido) AS medico,
        e.nombre_especialidad AS especialidad,
        ac.nombre_actividad AS actividad,
        d.nombre_destino AS destino
        FROM atenciones a
        INNER JOIN pacientes p ON a.id_paciente = p.id_paciente
        INNER JOIN medicos m ON a.id_medico = m.id_medico
        INNER JOIN especialidades e ON a.id_especialidad = e.id_especialidad
        INNER JOIN actividades ac ON a.id_actividad = ac.id_actividad
        LEFT JOIN destinos d ON a.id_destino = d.id_destino
        ORDER BY a.id_atencion DESC";

$atenciones = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sistema de Atención de Urgencias</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f1f4f8;
    margin: 30px;
}
h1 {
    color: #173b63;
    text-align: center;
}
h2 {
    color: #173b63;
}
.contenedor {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
}
form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
label {
    font-weight: bold;
}
input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
}
button {
    background: #1769aa;
    color: white;
    border: none;
    padding: 12px;
    cursor: pointer;
    border-radius: 5px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
th {
    background: #173b63;
    color: white;
}
</style>
</head>

<body>

<h1>Sistema de Atención de Urgencias</h1>

<div class="contenedor">
<h2>Registrar nueva atención</h2>

<form action="guardar.php" method="POST">

<div>
<label>Fecha de ingreso:</label>
<input type="date" name="fecha_ingreso" required>
</div>

<div>
<label>Paciente:</label>
<select name="id_paciente" required>
<option value="">Seleccionar paciente</option>
<?php while($p = $pacientes->fetch_assoc()) { ?>
<option value="<?= $p['id_paciente'] ?>">
<?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>Médico:</label>
<select name="id_medico" required>
<option value="">Seleccionar médico</option>
<?php while($m = $medicos->fetch_assoc()) { ?>
<option value="<?= $m['id_medico'] ?>">
<?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>Especialidad:</label>
<select name="id_especialidad" required>
<option value="">Seleccionar especialidad</option>
<?php while($e = $especialidades->fetch_assoc()) { ?>
<option value="<?= $e['id'] ?>">
<?= htmlspecialchars($e['nombre_especialidad']) ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>Actividad clínica:</label>
<select name="id_actividad" required>
<option value="">Seleccionar actividad</option>
<?php while($ac = $actividades->fetch_assoc()) { ?>
<option value="<?= $ac['id_actividad'] ?>">
<?= htmlspecialchars($ac['nombre_actividad']) ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>Destino del paciente:</label>
<select name="id_destino" required>
<option value="">Seleccionar destino</option>
<?php while($d = $destinos->fetch_assoc()) { ?>
<option value="<?= $d['id'] ?>">
<?= htmlspecialchars($d['nombre_destino']) ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>Diagnóstico:</label>
<input type="text" name="diagnostico" required>
</div>

<div>
<label>Fecha de alta:</label>
<input type="date" name="fecha_alta">
</div>

<div>
<button type="submit">Guardar atención</button>
</div>

</form>
</div>

<div class="contenedor">
<h2>Atenciones registradas</h2>

<table>
<tr>
<th>ID</th>
<th>Ingreso</th>
<th>Paciente</th>
<th>Médico</th>
<th>Especialidad</th>
<th>Actividad</th>
<th>Diagnóstico</th>
<th>Destino</th>
<th>Alta</th>
<th>Acciones</th>
</tr>

<?php while($a = $atenciones->fetch_assoc()) { ?>
<tr>
<td><?= $a['id_atencion'] ?></td>
<td><?= htmlspecialchars($a['fecha_ingreso']) ?></td>
<td><?= htmlspecialchars($a['paciente']) ?></td>
<td><?= htmlspecialchars($a['medico']) ?></td>
<td><?= htmlspecialchars($a['especialidad']) ?></td>
<td><?= htmlspecialchars($a['actividad']) ?></td>
<td><?= htmlspecialchars($a['diagnostico']) ?></td>
<td><?= htmlspecialchars($a['destino'] ?? '') ?></td>
<td><?= htmlspecialchars($a['fecha_alta'] ?? '') ?></td>
<td>
    <a href="editar.php?id=<?= $a['id_atencion'] ?>">Editar</a>
    |
    <a href="eliminar.php?id=<?= $a['id_atencion'] ?>"
       onclick="return confirm('¿Estás seguro de eliminar esta atención?')">
       Eliminar
    </a>
</td>
</tr>
<?php } ?>

</table>
</div>

</body>
</html>




<?php
require_once "conexion.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET["id"];

$sql = "SELECT * FROM atenciones WHERE id_atencion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$atencion = $resultado->fetch_assoc();

$stmt->close();

if (!$atencion) {
    die("Atención no encontrada.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fecha_ingreso = $_POST["fecha_ingreso"];
    $id_paciente = (int) $_POST["id_paciente"];
    $id_medico = (int) $_POST["id_medico"];
    $id_especialidad = (int) $_POST["id_especialidad"];
    $id_actividad = (int) $_POST["id_actividad"];
    $id_destino = (int) $_POST["id_destino"];
    $diagnostico = trim($_POST["diagnostico"]);
    $fecha_alta = !empty($_POST["fecha_alta"])
        ? $_POST["fecha_alta"]
        : null;

    $sql = "UPDATE atenciones SET
        fecha_ingreso = ?,
        id_paciente = ?,
        id_medico = ?,
        id_especialidad = ?,
        id_actividad = ?,
        id_destino = ?,
        diagnostico = ?,
        fecha_alta = ?
        WHERE id_atencion = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "siiiiiisi",
        $fecha_ingreso,
        $id_paciente,
        $id_medico,
        $id_especialidad,
        $id_actividad,
        $id_destino,
        $diagnostico,
        $fecha_alta,
        $id
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar: " . htmlspecialchars($stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar atención</title>
<style>
body {
    font-family: Arial;
    background: #f1f4f8;
    margin: 30px;
}
.contenedor {
    background: white;
    padding: 25px;
    border-radius: 10px;
    max-width: 700px;
    margin: auto;
}
label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
}
input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
}
button {
    margin-top: 20px;
    padding: 12px;
    background: #1769aa;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
</head>
<body>

<div class="contenedor">
<h1>Editar atención</h1>

<form method="POST">

<label>Fecha de ingreso:</label>
<input type="date" name="fecha_ingreso"
value="<?= htmlspecialchars($atencion['fecha_ingreso']) ?>" required>

<label>ID del paciente:</label>
<input type="number" name="id_paciente"
value="<?= $atencion['id_paciente'] ?>" required>

<label>ID del médico:</label>
<input type="number" name="id_medico"
value="<?= $atencion['id_medico'] ?>" required>

<label>ID de especialidad:</label>
<input type="number" name="id_especialidad"
value="<?= $atencion['id_especialidad'] ?>" required>

<label>ID de actividad:</label>
<input type="number" name="id_actividad"
value="<?= $atencion['id_actividad'] ?>" required>

<label>ID de destino:</label>
<input type="number" name="id_destino"
value="<?= $atencion['id_destino'] ?>" required>

<label>Diagnóstico:</label>
<input type="text" name="diagnostico"
value="<?= htmlspecialchars($atencion['diagnostico']) ?>" required>

<label>Fecha de alta:</label>
<input type="date" name="fecha_alta"
value="<?= htmlspecialchars($atencion['fecha_alta'] ?? '') ?>">

<button type="submit">Guardar cambios</button>
<a href="index.php">Volver</a>

</form>
</div>

</body>
</html>

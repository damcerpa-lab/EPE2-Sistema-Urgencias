
<?php
require_once "conexion.php";

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

    $sql = "INSERT INTO atenciones
    (fecha_ingreso, id_paciente, id_medico, id_especialidad,
     id_actividad, id_destino, diagnostico, fecha_alta)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "siiiiiss",
        $fecha_ingreso,
        $id_paciente,
        $id_medico,
        $id_especialidad,
        $id_actividad,
        $id_destino,
        $diagnostico,
        $fecha_alta
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();

} else {
    header("Location: index.php");
    exit;
}
?>

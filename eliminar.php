
<?php
require_once "conexion.php";

if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];

    $stmt = $conexion->prepare(
        "DELETE FROM atenciones WHERE id_atencion = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al eliminar la atención.";
    }

    $stmt->close();
} else {
    header("Location: index.php");
    exit;
}
?>

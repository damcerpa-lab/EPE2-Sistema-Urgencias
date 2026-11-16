<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "TU_CONTRASEÑA_MYSQL";
$basedatos = "epe2_urgencias";

$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>
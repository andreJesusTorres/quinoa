<?php
require("config.php");

function conectar()
{
    try {
        $conexion = mysqli_connect(server, usuario, clave, nombre);
        return $conexion;
    } catch (mysqli_sql_exception $e) {
        require("error.php");
        return null;
    }
}
?>
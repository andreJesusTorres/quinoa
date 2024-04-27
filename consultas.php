<?php
require ("config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);


function conectar()
{
    $conexion = mysqli_connect(server, usuario, clave, nombre);
    if (mysqli_connect_errno()) {
        require ("error.php");
        return null;
    }
    return $conexion;
}

if (isset($_POST["registro"])) {

    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $mail = $_POST["mail"];
    $phone = isset($_POST["phone"]) ? $_POST["phone"] : null;

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    if (strlen($name) >= 5 && strlen($name) <= 15 && strlen($pass) >= 5 && strlen($pass) <= 15) {
        if ($phone !== null) {
            $register = "INSERT INTO users (name, pass, mail, phone) VALUES ('$name', '$pass', '$mail', '$phone')";
        } else {
            $register = "INSERT INTO users (name, pass, mail) VALUES ('$name', '$pass', '$mail')";
        }

        $register_result = mysqli_query($conexion, $register);

        if ($register_result) {
            $save_register = "exito";
        } else {
            $error_register = "error";
        }
    } else {
        $characters = "error";
    }

    mysqli_close($conexion);
}


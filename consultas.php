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

function reservar($name, $mail, $phone, $date, $time, $people, $msg)
{
    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $check_user_query = "SELECT * FROM users WHERE name = '$name' AND mail = '$mail'";
    $check_user_result = mysqli_query($conexion, $check_user_query);

    if ($check_user_result && mysqli_num_rows($check_user_result) > 0) {

        echo "<script>
        if (confirm('¡Eres un cliente registrado!')) {
            window.location.href = 'inicioSesion.php';
        }
      </script>";
        exit();

    } else {
        $type = 'Invitado';
    }


    $insert_query = "INSERT INTO reserves (name, mail, phone, date, time, people, msg, type) 
                     VALUES ('$name', '$mail', '$phone', '$date', '$time', '$people', '$msg', '$type')";

    if (mysqli_query($conexion, $insert_query)) {
        mysqli_close($conexion);
        $sent_message = "Su reserva fue enviada.";
        return true;
    } else {
        mysqli_close($conexion);
        $error_message = "Error al enviar la reserva. Por favor, inténtelo de nuevo más tarde.";
        return false;
    }
}

function reservarCliente($name, $mail, $phone, $date, $time, $people, $msg)
{
    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $type = 'Cliente';


    $insert_query = "INSERT INTO reserves (name, mail, phone, date, time, people, msg, type) 
                     VALUES ('$name', '$mail', '$phone', '$date', '$time', '$people', '$msg', '$type')";

    if (mysqli_query($conexion, $insert_query)) {
        mysqli_close($conexion);
        $sent_message = "Su reserva fue enviada.";
        return true;
    } else {
        mysqli_close($conexion);
        $error_message = "Error al enviar la reserva. Por favor, inténtelo de nuevo más tarde.";
        return false;
    }
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

if (isset($_POST["login"])) {
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $login = "SELECT * FROM users WHERE name = '$name' AND pass = '$pass'";
    $login_result = mysqli_query($conexion, $login);

    if ($login_result && mysqli_num_rows($login_result) > 0) {
        $user = mysqli_fetch_assoc($login_result);
        mysqli_close($conexion);
        session_start();

        if ($user['name'] === 'Admin') {
            $_SESSION["login"] = [
                "name" => $user['name'],
                "role" => "admin"
            ];
            header("Location: indexAdmin.php");
            exit();
        } else {
            $_SESSION["login"] = [
                "name" => $user['name'],
                "role" => "cliente"
            ];
            header("Location: indexCliente.php");
            exit();
        }
    } else {
        header("Location: iniciosesion.php");
    }
}

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: iniciosesion.php");
    exit();
}


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
function getReservasCliente($mail)
{

    $conexion = conectar();
    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM reserves WHERE mail = '$mail'";
    $result = mysqli_query($conexion, $query);

    $reservas = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reservas[] = $row;
        }
    }

    mysqli_close($conexion);
    return $reservas;
}
function getReservaClientePorId($id_reserva)
{
    $conexion = conectar();
    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM reserves WHERE id = '$id_reserva'";
    $result = mysqli_query($conexion, $query);

    $reserva = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $reserva = mysqli_fetch_assoc($result);
    }

    mysqli_close($conexion);
    return $reserva;
}
function listarMenuIndex()
{
    $conexion = conectar();
    $menuItems = array();

    if ($conexion != null) {
        $sql = "SELECT * FROM menu WHERE state = 1 ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                $menuItems[] = $datos; // Agregar datos al array
            }
        }

        mysqli_close($conexion);
    }

    return $menuItems;
}
function listarMesas()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT * FROM tables ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                echo '
                    <tr>
                        <td>' . $datos["id"] . '</td>
                        <td>' . $datos["sites"] . '</td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="codigo" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminarMesa"></button>
                            </form>
                        </td>
                    </tr>
                ';
            }
        }
        mysqli_close($conexion);
    }
}
function listarMenu()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT * FROM menu ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                $estado_icono = ($datos["state"] == 1) ? 'img/verde.png' : 'img/rojo.png';

                echo '
                    <tr>
                        <td>' . $datos["id"] . '</td>
                        <td>' . $datos["name"] . '</td>
                        <td>' . $datos["descrip"] . '</td>
                        <td>' . $datos["price"] . '</td>
                        <td><img src="' . $datos["img"] . '" alt="' . $datos["name"] . '" width="50" height="50"></td>
                        <td><img src="' . $estado_icono . '" alt="' . $datos["name"] . '" width="20" height="20"></td>
                        <td>
                        <div class="d-flex align-items-center">
                            <form method="POST" action="modificarMenu.php">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button type="submit" class="btn btn-sm btn-outline-secondary bi bi-pencil"></button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminarMenu"></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                ';
            }
        }
        mysqli_close($conexion);
    }
}
function listarUsuarios()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT * FROM users ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                echo '
                <tr>
                <td>' . $datos["id"] . '</td>
                <td>' . $datos["name"] . '</td>
                <td>' . $datos["mail"] . '</td>
                <td>' . $datos["phone"] . '</td>
                <td>' . $datos["type"] . ' </td>
                <td>
                    <div class="d-flex align-items-center">
                        <form method="POST" action="modificarUsuario.php">
                            <input type="hidden" name="id" value="' . $datos["id"] . '">
                            <button type="submit" class="btn btn-sm btn-outline-secondary bi bi-pencil" name="modificarUsuario"></button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="id" value="' . $datos["id"] . '">
                            <button type="submit" class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminarUsuario"></button>
                        </form>
                    </div>
                </td>
            </tr>
            
                ';
            }
        }
        mysqli_close($conexion);
    }
}
function listarReservas()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT * FROM reserves ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                echo '
                    <tr>
                        <td>' . $datos["id"] . '</td>
                        <td>' . $datos["name"] . '</td>
                        <td>' . $datos["mail"] . '</td>
                        <td>' . $datos["phone"] . '</td>
                        <td>' . $datos["date"] . '</td>
                        <td>' . $datos["time"] . '</td>
                        <td>' . $datos["people"] . '</td>
                        <td>' . $datos["msg"] . '</td>
                        <td>' . $datos["type"] . '</td>
                        <td>
                        <div class="d-flex align-items-center">
                            <form method="POST" action="modificarReservaAdmin.php">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-secondary bi bi-pencil" name="modificarReserva"></button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminarReserva"></button>
                            </form>
                        </td>
                    </tr>
                ';
            }
        }
        mysqli_close($conexion);
    }
}
function eliminarUsuario($idUsuario)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "DELETE FROM users WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idUsuario);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);
        return $resultado;
    }
    return false;
}
function eliminarMenu($idMenu)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "DELETE FROM menu WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idMenu);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);
        return $resultado;
    }
    return false;
}
function eliminarMesa($idMesa)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "DELETE FROM tables WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idMesa);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);
        return $resultado;
    }
    return false;
}
function eliminarReserva($idReserva)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "DELETE FROM reserves WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idReserva);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);
        return $resultado;
    }
    return false;
}

if (isset($_POST["agregar_mesa"])) {
    $sites = $_POST["sites"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO tables (sites) VALUES (?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "s", $sites);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "Error al agregar la mesa: " . mysqli_error($conexion);
        } else {
            echo "Mesa agregada exitosamente.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

if (isset($_POST["agregar_menu"])) {
    $name = $_POST["name"];
    $descrip = $_POST["descrip"];
    $price = $_POST["price"];
    $img = $_FILES["img"]["name"];
    $temporal_img = $_FILES["img"]["tmp_name"];
    $state = ($_POST["state"] == "Disponible") ? 1 : 0;

    $rute = "img/food/" . $img;
    move_uploaded_file($temporal_img, $rute);

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO menu (name, descrip, price, img, state) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssdis", $name, $descrip, $price, $rute, $state);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "Error al agregar el menú: " . mysqli_error($conexion);
        } else {
            echo "Menú agregado exitosamente.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

if (isset($_POST["agregar_usuario"])) {
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO users (name, pass, mail, phone, type) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssss", $name, $pass, $mail, $phone, $type);


        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "Error al agregar el usuario: " . mysqli_error($conexion);
        } else {
            echo "Usuario agregado exitosamente.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

if (isset($_POST["modificar_menu"])) {
    var_dump($_POST);
    $id = $_POST["id"];
    $name = $_POST["name"];
    $descrip = $_POST["descrip"];
    $price = $_POST["price"];
    $img = $_FILES["img"]["name"];
    $temporal_img = $_FILES["img"]["tmp_name"];
    $state = ($_POST["state"] == "Disponible") ? 1 : 0;

    $rute = "img/food/" . $img;
    move_uploaded_file($temporal_img, $rute);

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE menu SET name=?, descrip=?, price=?, img=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssssi", $name, $descrip, $price, $rute, $state, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            $menuNoModificado = "error";
        } else {
            $menuModificado = "exito";
            header("Location:indexAdmin.php");
        }

        mysqli_stmt_close($stmt);
        var_dump($modificar);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_usuario"])) {
    var_dump($_POST);
    $id = $_POST["id"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE users SET name=?, pass=?, mail=?, phone=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssssi", $name, $pass, $mail, $phone, $type, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            $userNoModificado = "error";
        } else {
            $userModificado = "exito";
            header("Location:indexAdmin.php");
        }

        mysqli_stmt_close($stmt);
        var_dump($modificar);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_reserva"])) {
    $id = $_POST["id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE reserves SET date=?, time=?, people=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssi", $date, $time, $people, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            $usuarioNoModificado = "error";
        } else {
            $usuarioModificado = "exito";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_reserva_admin"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];
    $msg = $_POST["msg"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE reserves SET name=?, mail=?, phone=?, date=?, time=?, people=?, msg=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $mail, $phone, $date, $time, $people, $msg, $type, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            $reservaNoModificada = "error";
        } else {
            $reservaModificada = "exito";
            header("Location:indexAdmin.php");
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
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
        $type = 'Cliente';
        if ($phone !== null) {
            $register = "INSERT INTO users (name, pass, mail, phone,type) VALUES ('$name', '$pass', '$mail', '$phone', '$type')";
        } else {
            $register = "INSERT INTO users (name, pass, mail) VALUES ('$name', '$pass', '$mail','$type')";
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
    $mail = $_POST["mail"];
    $pass = $_POST["pass"];
    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $login = "SELECT * FROM users WHERE mail = '$mail' AND pass = '$pass'";
    $login_result = mysqli_query($conexion, $login);

    if ($login_result && mysqli_num_rows($login_result) > 0) {
        $user = mysqli_fetch_assoc($login_result);
        mysqli_close($conexion);
        session_start();
        $_SESSION["login"] = $user;

        // Redirigir según el tipo de usuario
        if ($user['type'] == 'Administrador') {
            header("Location: indexAdmin.php");
        } elseif ($user['type'] == 'Empleado') {
            header("Location: indexEmpleado.php");
        } elseif ($user['type'] == 'Cliente') {
            header("Location: indexCliente.php");
        } else {
            // Si el tipo de usuario no es ninguno de los esperados, redirigir a una página de error o manejarlo de otra manera
            header("Location: error.html");
        }
        exit();
    } else {
        $error_login = 'error';
    }

}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: iniciosesion.php");
    exit();
}

if (isset($_GET['eliminar_reserva'])) {
    $id = $_GET['id'];

    $conexion = conectar();
    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $query = "DELETE FROM reserves WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        header("Location: indexCliente.php");
        exit();
    } else {
        $error_message = "Error al eliminar la reserva: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
}

if (isset($_POST["modificar_datos"])) {
    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $id = $_POST["id"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE users SET name=?, mail=?, phone=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssi", $name, $mail, $phone, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            $usuarioNoModificado = "error";
        } else {
            $usuarioModificado = "exito";
            session_destroy();
            header("Location: iniciosesion.php");
            exit();
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conexion);
}

<?php
require ("config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);
function conectar()
{
    $conexion = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
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
    
    $check_user_query = "SELECT id FROM users WHERE mail = ?";
    $stmt_user = mysqli_prepare($conexion, $check_user_query);
    mysqli_stmt_bind_param($stmt_user, "s", $mail);
    mysqli_stmt_execute($stmt_user);
    $check_user_result = mysqli_stmt_get_result($stmt_user);

    if ($check_user_result && mysqli_num_rows($check_user_result) > 0) {
        $user_row = mysqli_fetch_assoc($check_user_result);
        $id_usuario = $user_row['id'];
    } else {
        $id_usuario = NULL;
    }

    mysqli_stmt_close($stmt_user);

    $check_reserve_query = "SELECT * FROM reserves WHERE id_usuario = ? AND date = ?";
    $stmt_reserve = mysqli_prepare($conexion, $check_reserve_query);
    mysqli_stmt_bind_param($stmt_reserve, "is", $id_usuario, $date);
    mysqli_stmt_execute($stmt_reserve);
    $check_reserve_result = mysqli_stmt_get_result($stmt_reserve);

    if ($check_reserve_result && mysqli_num_rows($check_reserve_result) > 0) {
        mysqli_stmt_close($stmt_reserve);
        mysqli_close($conexion);

        echo "<script>
        alert('Ya tiene una reserva para esa fecha.');
        window.location.href = 'index.php';
        </script>";
        exit();
    }

    mysqli_stmt_close($stmt_reserve);

    // Verificar disponibilidad de mesas
    $max_capacity = ($people <= 4) ? 4 : 8;

    $table_query = "SELECT id FROM tables WHERE id NOT IN (
                        SELECT table_num FROM reserves 
                        WHERE date = ? AND time = ?
                    ) AND sites >= ? AND sites <= ? ORDER BY sites ASC LIMIT 1";
    $stmt_table = mysqli_prepare($conexion, $table_query);
    mysqli_stmt_bind_param($stmt_table, "ssii", $date, $time, $people, $max_capacity);
    mysqli_stmt_execute($stmt_table);
    $table_result = mysqli_stmt_get_result($stmt_table);

    if (mysqli_num_rows($table_result) == 0) {
        mysqli_stmt_close($stmt_table);
        mysqli_close($conexion);
        echo "<script>
        alert('No hay mesas disponibles para esa franja horaria.');
        window.location.href = 'index.php';
        </script>";
        exit();
    }

    $table_row = mysqli_fetch_assoc($table_result);
    $table_num = $table_row['id'];
    mysqli_stmt_close($stmt_table);

    // Insertar la nueva reserva
    $type = 'Invitado';
    $insert_query = "INSERT INTO reserves (id_usuario, date, time, people, msg, type, table_num) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conexion, $insert_query);
    mysqli_stmt_bind_param($stmt_insert, "isssssi", $id_usuario, $date, $time, $people, $msg, $type, $table_num);

    if (mysqli_stmt_execute($stmt_insert)) {
        mysqli_stmt_close($stmt_insert);
        mysqli_close($conexion);

        echo "<script>
        alert('Su reserva fue enviada.');
        window.location.href = 'index.php';
        </script>";
        return true;
    } else {
        mysqli_stmt_close($stmt_insert);
        mysqli_close($conexion);

        echo "<script>
        alert('Error al enviar la reserva. Por favor, inténtelo de nuevo más tarde.');
        window.location.href = 'index.php';
        </script>";
        return false;
    }
}
function reservarCliente($id_usuario, $date, $time, $people, $msg)
{
    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $type = 'Cliente';

    $check_query = "SELECT * FROM reserves WHERE id_usuario = ? AND date = ?";
    $stmt = mysqli_prepare($conexion, $check_query);
    mysqli_stmt_bind_param($stmt, "is", $id_usuario, $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        echo "<script>
        alert('Ya tiene una reserva para esa fecha.');
        window.location.href = 'indexCliente.php';
        </script>";
        exit();
    }

    mysqli_stmt_close($stmt);

    $table_query = "SELECT id FROM tables WHERE id NOT IN (
                        SELECT table_num FROM reserves 
                        WHERE date = ? AND time = ?
                    ) AND sites >= ? ORDER BY sites ASC LIMIT 1";
    $stmt = mysqli_prepare($conexion, $table_query);
    mysqli_stmt_bind_param($stmt, "sss", $date, $time, $people);
    mysqli_stmt_execute($stmt);
    $table_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($table_result) == 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        echo "<script>
        alert('No hay mesas disponibles para esa franja horaria.');
        window.location.href = 'indexCliente.php';
        </script>";
        exit();
    }

    $table_row = mysqli_fetch_assoc($table_result);
    $table_num = $table_row['id'];
    mysqli_stmt_close($stmt);

    // Mensaje de depuración
    echo "Mesa seleccionada: " . $table_num;

    $insert_query = "INSERT INTO reserves (id_usuario, date, time, people, msg, type, table_num) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $insert_query);
    mysqli_stmt_bind_param($stmt, "isssssi", $id_usuario, $date, $time, $people, $msg, $type, $table_num);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        echo "<script>
        alert('Su reserva fue enviada.');
        window.location.href = 'indexCliente.php';
        </script>";
        exit();
    } else {
        // Mensaje de depuración
        echo "Error al insertar reserva: " . mysqli_error($conexion);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        echo "<script>
        alert('Su reserva no fue enviada. Intentelo nuevamente.');
        window.location.href = 'indexCliente.php';
        </script>";
        exit();
    }
}
function getReservasCliente($mail)
{
    $conexion = conectar();
    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Ajustar la consulta para realizar un JOIN con la tabla users
    $query = "SELECT reserves.* FROM reserves 
              INNER JOIN users ON reserves.id_usuario = users.id 
              WHERE users.mail = ?";
    
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $mail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $reservas = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reservas[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
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
                $menuItems[] = $datos;
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
                        <td>' . $datos["category"] . '</td>
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
function listarMenuEmpleado()
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
                        <td>' . $datos["category"] . '</td>
                        <td>' . $datos["price"] . '</td>
                        <td><img src="' . $datos["img"] . '" alt="' . $datos["name"] . '" width="50" height="50"></td>
                        <td><img src="' . $estado_icono . '" alt="' . $datos["name"] . '" width="20" height="20"></td>
                        <td>
                        <div class="d-flex align-items-center">
                            <form method="POST" action="modificarMenuEmpleado.php">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button type="submit" class="btn btn-sm btn-outline-secondary bi bi-pencil"></button>
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
                $estado = ($datos["state"] == 1) ? "img/verde.png" : "img/rojo.png";

                echo '
                <tr>
                <td>' . $datos["id"] . '</td>
                <td>' . $datos["name"] . '</td>
                <td>' . $datos["mail"] . '</td>
                <td>' . $datos["phone"] . '</td>
                <td>' . $datos["type"] . ' </td>
                <td><img src="' . $estado . '" alt="Estado" width="20" height="20"></td> 
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
                    <div class="d-flex align-items-center">
                    <form method="POST">
                        <input type="hidden" name="id" value="' . $datos["id"] . '">
                        <button type="submit" class="btn btn-sm btn-outline-success bi bi-arrow-up-square" name="activarUsuario"></button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="id" value="' . $datos["id"] . '">
                        <button type="submit" class="btn btn-sm btn-outline-warning bi bi-arrow-down-square" name="desactivarUsuario"></button>
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
function listarUsuariosEmpleado()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT * FROM users WHERE type = 'cliente' ORDER BY id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                $estado = ($datos["state"] == 1) ? "img/verde.png" : "img/rojo.png";
                echo '
                <tr>
                <td>' . $datos["id"] . '</td>
                <td>' . $datos["name"] . '</td>
                <td>' . $datos["mail"] . '</td>
                <td>' . $datos["phone"] . '</td>
                <td>' . $datos["type"] . ' </td>
                <td><img src="' . $estado . '" alt="Estado" width="20" height="20"></td> 
                <td>
                <div class="d-flex align-items-center">
                    <form method="POST">
                        <input type="hidden" name="id" value="' . $datos["id"] . '">
                        <button type="submit" class="btn btn-sm btn-outline-success bi bi-arrow-up-square" name="activarUsuarioEmpleado"></button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="id" value="' . $datos["id"] . '">
                        <button type="submit" class="btn btn-sm btn-outline-warning bi bi-arrow-down-square" name="desactivarUsuarioEmpleado"></button>
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
        $sql = "SELECT reserves.id, reserves.id_usuario, reserves.date, reserves.time, reserves.people, reserves.msg, reserves.type, reserves.table_num, users.name, users.mail, users.phone 
                FROM reserves 
                INNER JOIN users ON reserves.id_usuario = users.id 
                ORDER BY reserves.id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                echo '
                    <tr>
                        <td>' . $datos["id"] . '</td>
                        <td>' . $datos["id_usuario"] . '</td>
                        <td>' . $datos["name"] . '</td>
                        <td>' . $datos["mail"] . '</td>
                        <td>' . $datos["phone"] . '</td>
                        <td>' . $datos["date"] . '</td>
                        <td>' . $datos["time"] . '</td>
                        <td>' . $datos["people"] . '</td>
                        <td>' . $datos["msg"] . '</td>
                        <td>' . $datos["type"] . '</td>
                        <td>' . $datos["table_num"] . '</td>
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
                            </div>
                        </td>
                    </tr>
                ';
            }
        }
        mysqli_close($conexion);
    }
}
function listarReservasEmpleado()
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "SELECT reserves.id, reserves.id_usuario, reserves.date, reserves.time, reserves.people, reserves.msg, reserves.type, reserves.table_num, users.name, users.mail, users.phone 
                FROM reserves 
                INNER JOIN users ON reserves.id_usuario = users.id 
                ORDER BY reserves.id ASC";
        $consulta = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($consulta) > 0) {
            while ($datos = mysqli_fetch_assoc($consulta)) {
                echo '
                    <tr>
                        <td>' . $datos["id"] . '</td>
                        <td>' .$datos["id_usuario"] . '</td>
                        <td>' . $datos["name"] . '</td>
                        <td>' . $datos["mail"] . '</td>
                        <td>' . $datos["phone"] . '</td>
                        <td>' . $datos["date"] . '</td>
                        <td>' . $datos["time"] . '</td>
                        <td>' . $datos["people"] . '</td>
                        <td>' . $datos["msg"] . '</td>
                        <td>' . $datos["type"] . '</td>
                        <td>' . $datos["table_num"] . '</td>
                        <td>
                        <div class="d-flex align-items-center">
                            <form method="POST" action="modificarReservaEmpleado.php">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-secondary bi bi-pencil" name="modificarReserva"></button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="id" value="' . $datos["id"] . '">
                                <button class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminarReservaEmpleado"></button>
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
        // Verificar si el usuario tiene reservas asociadas
        $sql_verificar_reservas = "SELECT COUNT(*) FROM reserves WHERE id_usuario = ?";
        $consulta_verificar_reservas = mysqli_prepare($conexion, $sql_verificar_reservas);
        mysqli_stmt_bind_param($consulta_verificar_reservas, "i", $idUsuario);
        mysqli_stmt_execute($consulta_verificar_reservas);
        mysqli_stmt_bind_result($consulta_verificar_reservas, $count);
        mysqli_stmt_fetch($consulta_verificar_reservas);
        mysqli_stmt_close($consulta_verificar_reservas);
        
        if ($count > 0) {
            // Si el usuario tiene reservas, mostrar mensaje y no proceder con la eliminación
            echo "<script>
                alert('No se puede eliminar el usuario porque tiene reservas asociadas. Por favor, elimine sus reservas primero.');
                window.location.href = 'indexAdmin.php';
            </script>";
            return false;
        }
        
        // Si el usuario no tiene reservas asociadas, proceder con la eliminación
        $sql = "DELETE FROM users WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idUsuario);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);

        if ($resultado) {
            echo "<script>
                alert('Usuario eliminado correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al eliminar el usuario. Por favor, inténtelo de nuevo más tarde.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }

        return $resultado;
    }

    echo "<script>
        alert('Error en la conexión a la base de datos.');
        window.location.href = 'indexAdmin.php';
    </script>";

    return false;
}

function activarUsuario($id)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "UPDATE users SET state = 1 WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        if ($resultado) {
            echo "<script>
                alert('Usuario dado de alta.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo dar de alta al usuario.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }
    }
}
function desactivarUsuario($id)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "UPDATE users SET state = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        if ($resultado) {
            echo "<script>
                alert('Usuario dado de baja correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo dar de baja al usuario.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }
    }
}
function activarUsuarioEmpleado($id)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "UPDATE users SET state = 1 WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        if ($resultado) {
            echo "<script>
                alert('Usuario dado de alta.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo dar de alta al usuario.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        }
    }
}
function desactivarUsuarioEmpleado($id)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "UPDATE users SET state = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        if ($resultado) {
            echo "<script>
                alert('Usuario dado de baja correctamente.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo dar de baja al usuario.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        }
    }
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
        if ($resultado) {
            echo "<script>
                alert('Menú eliminado correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo eliminar el menú.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }
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
        if ($resultado) {
            echo "<script>
                alert('Mesa eliminada correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo eliminar la mesa.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }
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
        if ($resultado) {
            echo "<script>
                alert('Reserva eliminada correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo eliminar la reserva.');
                window.location.href = 'indexAdmin.php';
            </script>";
        }
        return $resultado;
    }
    return false;
}
function eliminarReservaEmpleado($idReserva)
{
    $conexion = conectar();
    if ($conexion != null) {
        $sql = "DELETE FROM reserves WHERE id = ?";
        $consulta = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($consulta, "i", $idReserva);
        $resultado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);
        mysqli_close($conexion);
        if ($resultado) {
            echo "<script>
                alert('Reserva eliminada correctamente.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        } else {
            echo "<script>
                alert('No se pudo eliminar la reserva.');
                window.location.href = 'indexEmpleado.php';
            </script>";
        }
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

        mysqli_stmt_bind_param($stmt, "i", $sites);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "<script>
                    alert('No se pudo agregar la mesa.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Mesa agregada correctamente.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}

if (isset($_POST["agregar_menu"])) {
    $name = $_POST["name"];
    $descrip = $_POST["descrip"];
    $category = $_POST["category"];
    $price = $_POST["price"];
    $img = $_FILES["img"]["name"];
    $temporal_img = $_FILES["img"]["tmp_name"];
    $state = ($_POST["state"] == "Disponible") ? 1 : 0;

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO menu (name, descrip, category, price, state) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "sssdi", $name, $descrip, $category, $price, $state);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "<script>
                    alert('No se pudo agregar el menú.');
                    window.location.href = 'indexAdmin.php';
                </script>";
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            exit;
        }

        $menu_id = mysqli_insert_id($conexion);

        $rute = "img/food/" . $menu_id . "_" . $img;
        if (!move_uploaded_file($temporal_img, $rute)) {
            echo "<script>
                    alert('Error al subir la imagen.');
                    window.history.back();
                </script>";
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            exit;
        }

        $sql_update = "UPDATE menu SET img = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conexion, $sql_update);

        mysqli_stmt_bind_param($stmt_update, "si", $rute, $menu_id);

        $result_update = mysqli_stmt_execute($stmt_update);

        if (!$result_update) {
            echo "<script>
                    alert('No se pudo agregar la ruta de la imagen.');
                    window.location.href = 'indexAdmin.php';
                </script>";
            mysqli_stmt_close($stmt_update);
            mysqli_close($conexion);
            exit;
        }

        echo "<script>
                alert('Menú agregado correctamente.');
                window.location.href = 'indexAdmin.php';
            </script>";

        mysqli_stmt_close($stmt_update);
        mysqli_close($conexion);
    }
}

if (isset($_POST["agregar_usuario"])) {
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $state = '1';
    $type = $_POST["type"];

    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO users (name, pass, mail, phone, type, state) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssss", $name, $hashed_pass, $mail, $phone, $type, $state);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            echo "<script>
                    alert('No se pudo agregar el usuario.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Usuario agregado correctamente.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}


if (isset($_POST["modificar_menu"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $descrip = $_POST["descrip"];
    $category = $_POST["category"];
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
        $sql = "UPDATE menu SET name=?, descrip=?, category=?, price=?, img=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssssi", $name, $descrip, $category, $price, $rute, $state, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            echo "<script>
                    alert('No se pudo modificar el menù.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Menú modificado correctamente.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_menu_empleado"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $descrip = $_POST["descrip"];
    $category = $_POST["category"];
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
        $sql = "UPDATE menu SET name=?, descrip=?, category=?, price=?, img=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssssi", $name, $descrip, $category, $price, $rute, $state, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            echo "<script>
                    alert('No se pudo modificar el menù.');
                    window.location.href = 'modificarMenuEmpleado.php';
                </script>";
        } else {
            echo "<script>
                    alert('Menú modificado correctamente.');
                    window.location.href = 'indexEmpleado.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_usuario"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE users SET name=?, mail=?, phone=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssi", $name, $mail, $phone, $type, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            echo "<script>
                    alert('No se pudo modificar el usuario.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Usuario modificado correctamente.');
                    window.location.href = 'indexAdmin.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
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
            echo "<script>
                    alert('No se pudo modificar la reserva.');
                    window.location.href = 'indexCliente.php';
                </script>";
        } else {
            echo "<script>
                    alert('Reserva modificada correctamente.');
                    window.location.href = 'indexCliente.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_reserva_empleado"])) {
    $id = $_POST["id"];
    $id_usuario = $_POST["id_usuario"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];
    $msg = $_POST["msg"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE reserves SET id_usuario=?, date=?, time=?, people=?, msg=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "ssssssi", $id_usuario, $date, $time, $people, $msg, $type, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            echo "<script>
                    alert('No se pudo modificar la reserva');
                    window.location.href = 'indexEmpleado.php';
                </script>";
        } else {
            echo "<script>
                    alert('Reserva modificada correctamente.');
                    window.location.href = 'indexEmpleado.php';
                </script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}

if (isset($_POST["modificar_reserva_admin"])) {
    $id = $_POST["id"];
    $id_usuario = $_POST["id_usuario"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];
    $msg = $_POST["msg"];
    $type = $_POST["type"];

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    } else {
        $sql = "UPDATE reserves SET id_usuario=?, date=?, time=?, people=?, msg=?, type=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param($stmt, "isssssi", $id_usuario, $date, $time, $people, $msg, $type, $id);

        $modificar = mysqli_stmt_execute($stmt);

        if (!$modificar) {
            echo "<script>
                    alert('No se pudo modificar la reserva');
                    window.location.href = 'indexAdmin.php';
                </script>";
        } else {
            echo "<script>
                    alert('Reserva modificada correctamente.');
                    window.location.href = 'indexAdmin.php';
                </script>";
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
    $state = '1';

    $conexion = conectar();

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $check_email_query = "SELECT * FROM users WHERE mail = '$mail'";
    $check_email_result = mysqli_query($conexion, $check_email_query);

    if (mysqli_num_rows($check_email_result) > 0) {
        echo "<script>
                alert('El correo electrónico ya está registrado.');
                window.location.href = 'registrocliente.php';
              </script>";
    } else {
        if (strlen($name) >= 5 && strlen($name) <= 15 && strlen($pass) >= 5 && strlen($pass) <= 15) {
            $type = 'Cliente';
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            if ($phone !== null) {
                $register = "INSERT INTO users (name, pass, mail, phone, type, state) VALUES ('$name', '$hashed_pass', '$mail', '$phone', '$type', '$state')";
            } else {
                $register = "INSERT INTO users (name, pass, mail, type, state) VALUES ('$name', '$hashed_pass', '$mail', '$type', '$state')";
            }

            $register_result = mysqli_query($conexion, $register);

            if ($register_result) {
                $id = mysqli_insert_id($conexion);

                session_start();

                $_SESSION["login"]['id'] = $id;
                $_SESSION["login"]['name'] = $name;
                $_SESSION["login"]['mail'] = $mail;
                $_SESSION["login"]['phone'] = $phone;
                $_SESSION["login"]['type'] = $type;

                echo "<script>
                        alert('Cliente registrado correctamente.');
                        window.location.href = 'indexCliente.php';
                      </script>";
            } else {
                echo "<script>
                        alert('No se pudo registrar el cliente.');
                        window.location.href = 'registrocliente.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Ingrese otros datos correctamente.');
                    window.location.href = 'registrocliente.php';
                  </script>";
        }
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

    $login = "SELECT * FROM users WHERE mail = ?";
    $stmt = mysqli_prepare($conexion, $login);
    mysqli_stmt_bind_param($stmt, "s", $mail);
    mysqli_stmt_execute($stmt);
    $login_result = mysqli_stmt_get_result($stmt);

    if ($login_result && mysqli_num_rows($login_result) > 0) {
        $user = mysqli_fetch_assoc($login_result);

        if (password_verify($pass, $user['pass'])) {
            if ($user['state'] == 0) {
                echo "<script>
                        alert('Cliente dado de baja. No se puede iniciar sesión');
                        window.location.href = 'index.php';
                    </script>";
            } else {
                session_start();
                $_SESSION["login"] = $user;

                if ($user['type'] == 'Administrador') {
                    header("Location: indexAdmin.php");
                } elseif ($user['type'] == 'Empleado') {
                    header("Location: indexEmpleado.php");
                } elseif ($user['type'] == 'Cliente') {
                    header("Location: indexCliente.php");
                } else {
                    header("Location: error.html");
                }
                mysqli_stmt_close($stmt);
                mysqli_close($conexion);
                exit();
            }
        } else {
            echo "<script>
                    alert('Contraseña incorrecta.');
                    window.location.href = 'iniciosesion.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('No se encontró el usuario.');
                window.location.href = 'iniciosesion.php';
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
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
        echo "<script>
                    alert('Reserva eliminada correctamente.');
                    window.location.href = 'indexCliente.php';
                </script>";
        exit();
    } else {
        echo "<script>
                    alert('No se pudo eliminar la reserva.');
                    window.location.href = 'indexCliente.php';
                </script>";
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
            echo "<script>
                    alert('No se pudieron modificar los datos.');
                    window.location.href = 'indexCliente.php';
                </script>";
        } else {
            if ($modificar) {
                $_SESSION['login']['name'] = $name;
                $_SESSION['login']['mail'] = $mail;
                $_SESSION['login']['phone'] = $phone;
            }
            echo "<script>
                    alert('Datos modificados correctamente.');
                    window.location.href = 'indexCliente.php';
                </script>";

            exit();
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conexion);
}

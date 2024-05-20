<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
} else {
    $conexion = conectar();
    $sql = "SELECT * FROM reserves WHERE id='" . $_POST["id"] . "'";
    $buscar = mysqli_query($conexion, $sql);
    if (mysqli_num_rows($buscar) > 0) {
        $reserve = mysqli_fetch_assoc($buscar);
    }
    mysqli_close($conexion);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Reserva</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/main.css" rel="stylesheet">

</head>

<body>

<header id="header" class="header fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="indexCliente.php" class="logo d-flex align-items-center me-auto me-lg-0">
                <h1>Quinoa<span>.</span></h1>
            </a>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="indexEmpleado.php#menu"> Home</a></li>
                    <li><a href="indexEmpleado.php#menu"> Lista menú</a></li>
                    <li><a href="indexEmpleado.php#usuarios">Lista usuarios</a></li>
                    <li><a href="indexEmpleado.php#reservas">Lista de reservas</a></li>
                </ul>
            </nav>
            <a class="btn-book-a-table" href="iniciosesion.php" name="logout">Cerrar Sesión</a>
            <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
            <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
        </div>
    </header>

    <main id="main">
        <div class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <ol>
                        <li><a href="indexEmpleado.php">Home</a></li>
                        <li>Bienvenido, <?php echo $_SESSION["login"]["name"]; ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <section id="book-a-table" class="book-a-table">
            <div class="section-header">
                <h2>Modificar Reserva</h2>
                <p> <span>Modifica los detalles de la reserva</span> </p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="php-email-form" data-aos="fade-up"
                data-aos-delay="100">
                <input type="hidden" name="id" value="<?php echo $reserve['id']; ?>">
                <div class="mb-3">
                    <label for="id_usuario" class="form-label">Código de Usuario:</label>
                    <input type="text" class="form-control" id="id_usuario" name="id_usuario"
                        value="<?php echo $reserve['id_usuario']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha:</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo $reserve['date']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Hora:</label>
                    <select class="form-select" name="time" id="time" aria-label="Seleccione la hora" required>
                        <option><?php echo $reserve['time']; ?></option>
                        <?php
                        $times = array("13:00 - 14:00", "14:00 - 15:00", "15:00 - 16:00");
                        foreach ($times as $option) {
                            $selected = ($option == $reserva['time']) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Cantidad de personas:</label>
                    <select class="form-select" name="people" id="people"
                        aria-label="Seleccione la cantidad de personas" required>
                        <option value=""><?php echo $reserve['people']; ?></option>
                        <option value="1">1 persona</option>
                        <option value="2">2 personas</option>
                        <option value="3">3 personas</option>
                        <option value="4">4 personas</option>
                        <option value="5">5 personas</option>
                        <option value="6">6 personas</option>
                        <option value="7">7 personas</option>
                        <option value="8">8 personas</option>
                    </select>
                    <div class="validate"></div>
                </div>
                <div class="mb-3">
                    <label for="msg" class="form-label">Mensaje:</label>
                    <textarea type="text" class="form-control" id="msg" name="msg"
                        required><?php echo $reserve['msg']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipo de usuario:</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="Invitado" <?php if ($reserve['type'] == 'Invitado')
                            echo 'selected'; ?>>Invitado
                        </option>
                        <option value="Cliente" <?php if ($reserve['type'] == 'Cliente')
                            echo 'selected'; ?>>Cliente
                        </option>
                    </select>
                </div>

                <div class="text-center mb-3">
                    <button type="submit" name="modificar_reserva_empleado">Actualizar reserva</button>
                </div>
            </form>
        </section>
    </main>

    <footer id="footer" class="footer">

        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div>
                        <h4>Dirección</h4>
                        <p>
                            Elche, Alicante<br>
                            <br>
                        </p>
                    </div>

                </div>

                <div class="col-lg-3 col-md-6 footer-links d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Reservas</h4>
                        <p>
                            <strong>Teléfono:</strong> +34 666000111<br>
                            <strong>Email:</strong> quinoa@quinoa.com<br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 footer-links d-flex">
                    <i class="bi bi-clock icon"></i>
                    <div>
                        <h4>Horario</h4>
                        <p>
                            <strong>Lunes a Sábado de 13:00 a 16:00</strong><br>
                            Domingos: Cerrado
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Follow Us</h4>
                    <div class="social-links d-flex">
                        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; <strong><span>Quinoa</span></strong>.
            </div>
        </div>

    </footer>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>


</body>

</html>
<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];
}

$id_reserva = $_GET["id"];
$reserva = getReservaClientePorId($id_reserva);

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
                    <li><a href="indexCliente.php#hero">Inicio</a></li>
                    <li><a href="#about">Sobre Nosotros</a></li>
                    <li><a href="#menu">La Carta</a></li>
                    <li><a href="reservaCliente.php">Reservar</a></li>
                    <li><a href="#contact">Contacto</a></li>
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
                        <li><a href="indexCliente.php">Home</a></li>
                        <li>Bienvenido, <?php echo $_SESSION["login"]["name"]; ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <section id="book-a-table" class="book-a-table">
            <div class="section-header">
                <h2>Estás reservando como cliente.</h2>
                <p> <span>Modifica tu reserva</span> </p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
                <input type="hidden" name="id" value="<?php echo $id_reserva; ?>">
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo $reserva['date']; ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="time" class="form-label">Hora</label>
                        <select class="form-select" name="time" id="time" aria-label="Seleccione la hora" required>
                            <option value="" selected>Seleccione la hora</option>
                            <?php
                            $times = array("13:00 - 14:00", "14:00 - 15:00", "15:00 - 16:00");
                            foreach ($times as $option) {
                                $selected = ($option == $reserva['time']) ? 'selected' : '';
                                echo "<option value='$option' $selected>$option</option>";
                            }
                            ?>
                        </select>
                        <div class="validate"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="people" class="form-label">Personas</label>
                        <select class="form-select" name="people" id="people"
                            aria-label="Seleccione la cantidad de personas" required>
                            <option value="" selected>Seleccione la cantidad de personas</option>
                            <?php
                            $max_personas = 8;
                            for ($i = 1; $i <= $max_personas; $i++) {
                                $selected = ($i == $reserva['people']) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i persona" . ($i > 1 ? 's' : '') . "</option>";
                            }
                            ?>
                        </select>
                        <div class="validate"></div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" name="modificar_reserva">Actualizar
                        Reserva</button>
                </div>
            </form>
            </div>
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
<?php
require_once ("consultas.php");
session_start();

$sent_message = "";
$error_message = "";

if (!isset($_SESSION["login"])) {
  header("location: iniciosesion.php");
  exit();
} else {
  $name = $_SESSION["login"]["name"];
  $conexion = conectar();
  $list = "SELECT * FROM users WHERE name='$name'";
  $list_result = mysqli_query($conexion, $list);
  if (mysqli_num_rows($list_result) > 0) {
    $user = mysqli_fetch_assoc($list_result);
  }
  mysqli_close($conexion);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $mail = $_POST["mail"];
  $phone = $_POST["phone"];
  $date = $_POST["date"];
  $time = $_POST["time"];
  $people = $_POST["people"];
  $msg = $_POST["message"];

  if (reservarCliente($name, $mail, $phone, $date, $time, $people, $msg)) {
    $sent_message = 'Su reserva fue enviada.';
  } else {
    $error_message = 'Error al enviar la reserva. Por favor, inténtelo de nuevo más tarde.';
  }
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
        <h1>Bienvenido, <?php echo $_SESSION["login"]["name"]; ?><span>.</span></h1>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="indexCliente.php">Inicio</a></li>
          <li><a href="#about">Sobre Nosotros</a></li>
          <li><a href="#menu">La Carta</a></li>
          <li><a href="reservaCliente.php">Reserva</a></li>
          <li><a href="#contact">Contacto</a></li>

      </nav>

      <a class="btn-book-a-table" href="index.php" name="logout">Cerrar Sesión</a>
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header>

  <main id="main">

    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">

          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Reserva</li>
          </ol>
        </div>

      </div>
    </div>

    <section class="sample-page">
      <div class="container" data-aos="fade-up">

        <section id="book-a-table" class="book-a-table">
          <div class="container" data-aos="fade-up">
            <div class="section-header">
              <h2>Estás reservando como cliente.</h2>
              <p> <span>Reserva una Mesa</span> </p>
            </div>

            <div class="row g-0">

              <div class="col-lg-4 reservation-img" style="background-image: url(assets/img/reservation.jpg);"
                data-aos="zoom-out" data-aos-delay="200"></div>

              <div class="col-lg-8 d-flex align-items-center reservation-form-bg">
                <form method="post" role="form" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
                  <div class="row gy-4">
                    <div class="col-lg-4 col-md-6">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Nombre"
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars"
                        value="<?php echo $user["name"]; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="email" class="form-control" name="mail" id="email" placeholder="Email"
                        data-rule="email" data-msg="Please enter a valid email" value="<?php echo $user["mail"]; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="text" class="form-control" name="phone" id="phone" placeholder="Teléfono"
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars"
                        value="<?php echo $user["phone"]; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="date" name="date" class="form-control" id="date" placeholder="Fecha"
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <select class="form-select" name="time" id="time" aria-label="Seleccione la hora">
                        <option value="" selected>Seleccione la hora</option>
                        <option value="13:00 - 14:00">13:00 - 14:00</option>
                        <option value="14:00 - 15:00">14:00 - 15:00</option>
                        <option value="15:00 - 16:00">15:00 - 16:00</option>
                      </select>
                      <div class="validate"></div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                      <select class="form-select" name="people" id="people"
                        aria-label="Seleccione la cantidad de personas">
                        <option value="" selected>Seleccione la cantidad de personas</option>
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

                  </div>
                  <div class="form-group mt-3">
                    <textarea class="form-control" name="message" rows="5" placeholder="Mensaje"></textarea>
                    <div class="validate"></div>
                  </div>
                  <div class="mb-3">
                    <div class="loading">Cargando</div>
                  </div>
                  <div class="text-center"><button type="submit" name="reservarCliente">Reserva</button></div>
                </form>
              </div>

            </div>

          </div>
        </section>

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

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <script src="assets/js/main.js"></script>

</body>

</html>
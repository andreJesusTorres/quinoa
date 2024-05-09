<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
  header("location: iniciosesion.php");
  exit();
}

$reservas = getReservasCliente($_SESSION["login"]["mail"]);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Restaurante Quinoa</title>
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
                <li>
                    Bienvenido, <?php echo $_SESSION["login"]["name"]; ?>
                </li>
            </ol>
            <div class="dropdown book-a-table">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="img/usuario.png" alt="Perfil" width="30" height="30">
                </a>
                <div class="dropdown-menu dropdown-menu-end p-4" id="dropdownMenu"
                    style="width: 300px;">
                    <form method="POST" class="text-center php-email-form">
                        <h5 class="mb-4">Modificar datos</h5>
                        <div class="form-group mb-3">
                        <input type="hidden" class="form-control" id="floatingNombre" name="id"
                                placeholder="" value="<?php echo $_SESSION['login']['id']; ?>"
                                required>
                            <input type="text" class="form-control" id="floatingNombre" name="name"
                                placeholder="Nombre de usuario" value="<?php echo $_SESSION['login']['name']; ?>"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" id="floatingEmail" name="mail"
                                placeholder="Correo electrónico"
                                value="<?php echo $_SESSION['login']['mail']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" id="floatingPhone" name="phone"
                                placeholder="Teléfono" value="<?php echo $_SESSION['login']['phone']; ?>"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="modificar_datos">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




    <section id="hero" class="hero d-flex align-items-center section-bg">
      <div class="container">
        <div class="row justify-content-between gy-5">
          <div
            class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-start">
            <h2 data-aos="fade-up">Disfruta de nuestra comida vegetariana y vegana, con opciones sin gluten.
              <br>100% Ecológico.
            </h2>
            <p data-aos="fade-up" data-aos-delay="100">Desde hace más de 10 años</p>
            <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
              <a href="reservaCliente.php" class="btn-book-a-table">Reserva una mesa</a>
            </div>
            <p class="mt-3 text-center" data-aos="fade-up" data-aos-delay="200">
              ¿Has reservado ya? <a href="#" data-bs-toggle="modal" data-bs-target="#modalReservas">Mira aquí</a>.
            </p>
          </div>
          <div class="col-lg-5 order-1 order-lg-2 text-center text-lg-start">
            <img src="assets/img/hero-img.png" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="300">
          </div>
        </div>
      </div>
    </section>
    <div id="modalReservas" class="modal fade" tabindex="-1" aria-labelledby="modalReservasLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalReservasLabel">Mis Reservas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php if (empty($reservas)): ?>
              <p>No tienes reservas actualmente.</p>
            <?php else: ?>
              <table class="table">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personas</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody style="white-space: nowrap;">
                  <?php foreach ($reservas as $reserva): ?>
                    <tr>
                      <td><?php echo $reserva['date']; ?></td>
                      <td><?php echo $reserva['time']; ?></td>
                      <td><?php echo $reserva['people']; ?></td>
                      <td>
                        <a href="modificarReserva.php?id=<?php echo $reserva['id']; ?>" class="btn btn-primary btn-sm"
                          style="display: inline-block;"><i class="bi bi-pencil-square"></i></a>
                        <a href="reservaCliente.php?eliminar_reserva&id=<?php echo $reserva['id']; ?>"
                          class="btn btn-danger btn-sm" style="display: inline-block;"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

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
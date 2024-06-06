<?php
require_once("consultas.php");

session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["login"]["id"]; 
    $name = $_SESSION["login"]["name"];
    $mail = $_SESSION["login"]["mail"];
    $phone = $_SESSION["login"]["phone"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];
    $msg = $_POST["msg"];

    if (reservarCliente($_SESSION["login"]["id"], $date, $time, $people, $msg)) {
        $sent_message = 'Su reserva fue enviada correctamente.';
    } else {
        $error_message = 'Hubo un error al realizar la reserva. Inténtelo nuevamente.';
    }
    
}

$reservas = getReservasCliente($_SESSION["login"]["mail"]);
$menuItems = listarMenuIndex();
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
          <li><a href="#hero">Inicio</a></li>
          <li><a href="#about">Sobre Nosotros</a></li>
          <li><a href="#menu">La Carta</a></li>
          <li><a href="#reserva">Reservar</a></li>
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
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
              aria-expanded="false">
              <img src="img/usuario.png" alt="Perfil" width="30" height="30">
            </a>
            <div class="dropdown-menu dropdown-menu-end p-4" id="dropdownMenu" style="width: 300px;">
          <p class="mt-3 text-center" data-aos-delay="200">
            Mira tus reservas <a href="#" data-bs-toggle="modal" data-bs-target="#modalReservas">aquí</a>.
          </p>  
              <form method="POST" class="text-center php-email-form">
                <h5 class="mb-4">Modificar datos</h5>
                <div class="form-group mb-3">
                  <input type="hidden" class="form-control" id="floatingNombre" name="id" placeholder=""
                    value="<?php echo $_SESSION['login']['id']; ?>" required>
                  <input type="text" class="form-control" id="floatingNombre" name="name"
                    placeholder="Nombre de usuario" value="<?php echo $_SESSION['login']['name']; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <input type="email" class="form-control" id="floatingEmail" name="mail"
                    placeholder="Correo electrónico" value="<?php echo $_SESSION['login']['mail']; ?>" required>
                </div>
                <div class="form-group mb-3">
                  <input type="text" class="form-control" id="floatingPhone" name="phone" placeholder="Teléfono"
                    value="<?php echo $_SESSION['login']['phone']; ?>" required>
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
              <a href="#reserva" class="btn-book-a-table">Reserva una mesa</a>
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

    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Descubre Más</h2>
          <p><span>Sobre Nosotros</span></p>
        </div>

        <div class="row gy-4">
          <div class="col-lg-7 position-relative about-img" style="background-image: url(assets/img/about.jpg) ;"
            data-aos="fade-up" data-aos-delay="150">
            <div class="call-us position-absolute">
              <h4>Reserva una mesa</h4>
            <p>+34  666 000 111</p>
          </div>
        </div>
        <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
          <div class="content ps-0 ps-lg-5">
            <p>
              En Quinoa, nos dedicamos a ofrecer una experiencia gastronómica única y memorable que celebra la diversidad y riqueza de la cocina vegana y vegetariana. Nuestro compromiso con la calidad comienza con la selección de ingredientes frescos, 100% ecológicos y orgánicos, asegurándonos de que cada plato no solo sea delicioso, sino también nutritivo y sostenible. Nos enorgullece que el 50% de nuestros productos sean de origen local, apoyando así a los agricultores y productores de nuestra comunidad.
            </p>
            <p>
                Nuestros chefs talentosos y apasionados son verdaderos artistas culinarios, que combinan técnicas innovadoras con recetas tradicionales para crear platos que deleitan todos los sentidos. Cada creación es una obra maestra que refleja nuestra filosofía de respetar el medio ambiente mientras promovemos un estilo de vida saludable.
            </p>
            <p>
                En Quinoa, hemos creado un ambiente acogedor y elegante donde cada detalle está diseñado para tu comodidad y disfrute. Desde la decoración hasta el servicio, nos esforzamos por hacer de cada visita una experiencia especial que va más allá de una simple comida.
            </p>
            <p>
                Te invitamos a descubrir el placer de una cocina vegana y vegetariana excepcional en Quinoa. Ven y únete a nosotros para una experiencia gastronómica que recordarás mucho después de haber terminado tu última comida. Aquí, cada bocado es una celebración de sabor, salud y bienestar.
            </p>
            </div>
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
                        <div class="d-flex align-items-center">
                          <a href="modificarReserva.php?id=<?php echo $reserva['id']; ?>"
                            class="btn btn-sm btn-outline-secondary bi bi-pencil"></a>
                          <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                            <button class="btn btn-sm btn-outline-danger bi bi-trash" name="eliminar_reserva_cliente"></button>
                          </form>
                        </div>
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

    <section id="menu" class="menu">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Menu</h2>
          <p>Nuestra <span>Carta</span></p>

          <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">

            <li class="nav-item">
              <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-starters">
                <h4>Entrantes</h4>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-main">
                <h4>Platos Principales</h4>
              </a>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-desserts">
                <h4>Postres</h4>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-drinks">
                <h4>Bebidas</h4>
              </a>
            </li>

          </ul>
        </div>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="300">

          <div class="tab-pane fade active show" id="menu-starters">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Entrantes</h3>
            </div>
            <div class="row gy-5">
              <?php
              foreach ($menuItems as $item) {
                if ($item['category'] === 'Entrante') {
                  echo '<div class="col-lg-4 menu-item">';
                  echo '<a href="' . $item["img"] . '" class="glightbox"><img src="' . $item["img"] . '" class="menu-img img-fluid" alt=""></a>';
                  echo '<h4>' . $item["name"] . '</h4>';
                  echo '<p class="ingredients">' . $item["descrip"] . '</p>';
                  echo '<p class="price">' . $item["price"] . '€</p>';
                  echo '</div>';
                }
              }
              ?>
            </div>
          </div>

          <div class="tab-pane fade" id="menu-main">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Platos Principales</h3>
            </div>
            <div class="row gy-5">
              <?php
              foreach ($menuItems as $item) {
                if ($item['category'] === 'Principal') {
                  echo '<div class="col-lg-4 menu-item">';
                  echo '<a href="' . $item["img"] . '" class="glightbox"><img src="' . $item["img"] . '" class="menu-img img-fluid" alt=""></a>';
                  echo '<h4>' . $item["name"] . '</h4>';
                  echo '<p class="ingredients">' . $item["descrip"] . '</p>';
                  echo '<p class="price">' . $item["price"] . '€</p>';
                  echo '</div>';
                }
              }
              ?>
            </div>
          </div>

          <div class="tab-pane fade" id="menu-desserts">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Postres</h3>
            </div>
            <div class="row gy-5">
              <?php
              foreach ($menuItems as $item) {
                if ($item['category'] === 'Postre') {
                  echo '<div class="col-lg-4 menu-item">';
                  echo '<a href="' . $item["img"] . '" class="glightbox"><img src="' . $item["img"] . '" class="menu-img img-fluid" alt=""></a>';
                  echo '<h4>' . $item["name"] . '</h4>';
                  echo '<p class="ingredients">' . $item["descrip"] . '</p>';
                  echo '<p class="price">' . $item["price"] . '€</p>';
                  echo '</div>';
                }
              }
              ?>
            </div>
          </div>

          <div class="tab-pane fade" id="menu-drinks">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Bebidas</h3>
            </div>
            <div class="row gy-5">
              <?php
              foreach ($menuItems as $item) {
                if ($item['category'] === 'Bebida') {
                  echo '<div class="col-lg-4 menu-item">';
                  echo '<a href="' . $item["img"] . '" class="glightbox"><img src="' . $item["img"] . '" class="menu-img img-fluid" alt=""></a>';
                  echo '<h4>' . $item["name"] . '</h4>';
                  echo '<p class="ingredients">' . $item["descrip"] . '</p>';
                  echo '<p class="price">' . $item["price"] . '€</p>';
                  echo '</div>';
                }
              }
              ?>
            </div>
          </div>

        
        </div>


      </div>

      </div>
    </section>

    <section id="reservar" class="sample-page">
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
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars" required
                        value="<?php echo isset($_SESSION['login']['name']) ? $_SESSION['login']['name'] : ''; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="email" class="form-control" name="mail" id="mail" placeholder="Email"
                        data-rule="email" data-msg="Please enter a valid email" required
                        value="<?php echo isset($_SESSION['login']['mail']) ? $_SESSION['login']['mail'] : ''; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="text" class="form-control" name="phone" id="phone" placeholder="Teléfono"
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars"
                        value="<?php echo isset($_SESSION['login']['phone']) ? $_SESSION['login']['phone'] : ''; ?>">
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <input type="date" name="date" class="form-control" id="date" placeholder="Fecha"
                        data-rule="minlen:4" data-msg="Please enter at least 4 chars" required>
                      <div class="validate"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                      <select class="form-select" name="time" id="time" aria-label="Seleccione la hora" required>
                        <option value="" selected>Seleccione la hora</option>
                        <option value="13:00 - 14:00">13:00 - 14:00</option>
                        <option value="14:00 - 15:00">14:00 - 15:00</option>
                        <option value="15:00 - 16:00">15:00 - 16:00</option>
                      </select>
                      <div class="validate"></div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                      <select class="form-select" name="people" id="people"
                        aria-label="Seleccione la cantidad de personas" required>
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
                    <textarea class="form-control" name="msg" rows="5" placeholder="Mensaje"></textarea>
                    <div class="validate"></div>
                  </div>
                  <div class="mb-3">
                    <div class="loading">Cargando</div>
                  </div>
                  <div class="text-center"><button type="submit">Reserva como cliente</button></div>
                </form>

              </div>
            </div>
          </div>
        </section>
      </div>
    </section>
    
  <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <p> <span>Donde Estamos</span></p>
        </div>

        <div class="mb-3">
          <iframe style="border:0; width: 100%; height: 350px;"
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
            frameborder="0" allowfullscreen></iframe>
        </div>

        <div class="row gy-4">

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-map flex-shrink-0"></i>
              <div>
                <h3>Dirección</h3>
                <p>Elche, Alicante</p>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email</h3>
                <p>contacto@quinoa.com</p>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Llámanos</h3>
                <p>+34 600 123 123</p>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-share flex-shrink-0"></i>
              <div>
                <h3>Horario</h3>
                <div><strong>Lunes a Sábado </strong> de 13:00 a 16:00
                  <strong>Domingo:</strong> Cerrado
                </div>
              </div>
            </div>
          </div>

        </div>

        <form action="forms/contact.php" method="post" role="form" class="php-email-form p-3 p-md-4">
          <div class="row">
            <div class="col-xl-6 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
            </div>
            <div class="col-xl-6 form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
          </div>
          <div class="my-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Enviar</button></div>
        </form>

      </div>
    </section>

  <footer id="footer" class="footer">
    <div class="container">
      <div class="row gy-3">
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-geo-alt icon"></i>
          <div>
            <h4>Dirección</h4>
            <p>
              A108 Adam Street <br>
              New York, NY 535022 - US<br>
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-links d-flex">
          <i class="bi bi-telephone icon"></i>
          <div>
            <h4>Reservas</h4>
            <p>
              <strong>Teléfono:</strong> +34 666 000 111<br>
              <strong>Email:</strong> quinoa@ejemplo.com<br>
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-links d-flex">
          <i class="bi bi-clock icon"></i>
          <div>
            <h4>Horarios</h4>
            <p>
              <strong>Lunes-Sábado: 11AM</strong> - 23PM<br>
              Domingo: Cerrado
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-links d-flex">
          <i class="bi bi-share icon"></i>
          <div>
            <h4>Redes Sociales</h4>
            <div class="social-links d-flex">
              <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
              <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>
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
<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["modificarTipoUsuario"])) {
        $idUsuario = $_POST["id"];
        $nuevoTipo = $_POST["tipo_usuario"];

        if (modificarTipoUsuario($idUsuario, $nuevoTipo)) {
            header("Location: indexAdmin.php");
            exit();
        } else {
            echo "Error al modificar el tipo de usuario";
        }
    } elseif (isset($_POST["eliminarUsuario"])) {
        $idUsuario = $_POST["id"];

        if (eliminarUsuario($idUsuario)) {
            header("Location: indexAdmin.php");
            exit();
        } else {
            echo "Error al eliminar el usuario";
        }
    } elseif (isset($_POST["eliminarMesa"])) {
        $idMesa = $_POST["codigo"];

        if (eliminarMesa($idMesa)) {
            header("Location: indexAdmin.php");
            exit();
        } else {
            echo "Error al eliminar la mesa";
        }
    } elseif (isset($_POST["eliminarMenu"])) {
        $idMenu = $_POST["id"];

        if (eliminarMenu($idMenu)) {
            header("Location: indexAdmin.php");
            exit();
        } else {
            echo "Error al eliminar el menú";
        }
    } elseif (isset($_POST["eliminarReserva"])) {
        $idReserva = $_POST["id"];

        if (eliminarReserva($idReserva)) {
            header("Location: indexAdmin.php");
            exit();
        } else {
            echo "Error al eliminar la reserva";
        }
    }
}

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
                        <div class="dropdown-menu dropdown-menu-end p-4" id="dropdownMenu" style="width: 300px;">
                            <form method="POST" class="text-center php-email-form">
                                <h5 class="mb-4">Modificar datos</h5>
                                <div class="form-group mb-3">
                                    <input type="hidden" class="form-control" id="floatingNombre" name="id"
                                        placeholder="" value="<?php echo $_SESSION['login']['id']; ?>" required>
                                    <input type="text" class="form-control" id="floatingNombre" name="name"
                                        placeholder="Nombre de usuario"
                                        value="<?php echo $_SESSION['login']['name']; ?>" required>
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
                                <button type="submit" class="btn btn-primary btn-block"
                                    name="modificar_datos">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section id="book-a-table" class="book-a-table">
            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Lista de mesas</h2>
                    <p> <span>Mesas</span> </p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Numero de mesa</th>
                                <th scope="col">Sitios</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php listarMesas(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <section id="book-a-table" class="book-a-table">
            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Lista de menú</h2>
                    <p> <span>Menú</span> </p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Imagen</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php listarMenu(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="book-a-table" class="book-a-table">
            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Lista de usuarios</h2>
                    <p> <span>Usuarios</span> </p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php listarUsuarios(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="book-a-table" class="book-a-table">
            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Lista de reservas</h2>
                    <p> <span>Reservas</span> </p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Hora</th>
                                <th scope="col">Personas</th>
                                <th scope="col">Mensaje</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php listarReservas(); ?>
                        </tbody>
                    </table>
                </div>
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
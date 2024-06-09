<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
} else {
    $conexion = conectar();
    $sql = "SELECT * FROM menu WHERE id='" . $_POST["id"] . "'";
    $buscar = mysqli_query($conexion, $sql);
    if (mysqli_num_rows($buscar) > 0) {
        $menu = mysqli_fetch_assoc($buscar);
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
                    <li><a href="indexAdmin.php"> Home</a></li>
                    <li><a href="indexAdmin.php#mesas"> Lista mesas</a></li>
                    <li><a href="indexAdmin.php#menu"> Lista menú</a></li>
                    <li><a href="indexAdmin.php#usuarios">Lista usuarios</a></li>
                    <li><a href="indexAdmin.php#reservas">Lista de reservas</a></li>
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
                        <li><a href="indexAdmin.php">Home</a></li>
                        <li>Bienvenido, <?php echo $_SESSION["login"]["name"]; ?></li>
                    </ol>
                </div>
            </div>
        </div>

        <section id="book-a-table" class="book-a-table">
            <div class="section-header">
                <h2>Modificar Menú</h2>
                <p> <span>Modifica los detalles del menú</span> </p>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="php-email-form" data-aos="fade-up"
                data-aos-delay="100">
                <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="name" value="<?php echo $menu['name']; ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descrip" rows="3"
                        required><?php echo $menu['descrip']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Categoría:</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="" selected><?php echo $menu['category']; ?></option>
                        <option value="Entrante" <?php if ($menu['category'] === 'Entrante')
                            echo 'selected'; ?>>Entrante
                        </option>
                        <option value="Principal" <?php if ($menu['category'] === 'Principal')
                            echo 'selected'; ?>>
                            Principal</option>
                        <option value="Postre" <?php if ($menu['category'] === 'Postre')
                            echo 'selected'; ?>>Postre
                        </option>
                        <option value="Bebida" <?php if ($menu['category'] === 'Bebida')
                            echo 'selected'; ?>>Bebida
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" class="form-control" id="precio" name="price"
                        value="<?php echo $menu['price']; ?>" required>
                </div>
                <div class="mb-3">
                    <input type="hidden" name="img_actual" value="<?php echo $menu['img']; ?>">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <input type="file" class="form-control" id="imagen" name="img" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select class="form-control" id="estado" name="state" required>
                        <option value="Disponible" <?php if ($menu['state'] == 1)
                            echo "selected"; ?>>Disponible</option>
                        <option value="No Disponible" <?php if ($menu['state'] == 0)
                            echo "selected"; ?>>No Disponible
                        </option>
                    </select>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" name="modificar_menu">Actualizar Menú</button>
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
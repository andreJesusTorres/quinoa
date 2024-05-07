<?php
require_once ("consultas.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("location: iniciosesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Manejar la actualización de la reserva aquí
    // Obtener los datos del formulario enviado por POST
    $id = $_POST["id"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $people = $_POST["people"];

    // Llamar a la función para actualizar la reserva en la base de datos
    if (actualizarReserva($id, $date, $time, $people)) {
        $success_message = 'Reserva actualizada exitosamente.';
    } else {
        $error_message = 'Error al actualizar la reserva.';
    }
}

// Obtener los detalles de la reserva a partir del ID pasado por GET
$id = $_GET["id"];
$reserva = getReservaPorId($id);

// Verificar si la reserva existe
if (!$reserva) {
    // Redireccionar si la reserva no existe o no pertenece al usuario actual
    header("location: indexCliente.php");
    exit();
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
                    <li><a href="reserva.php">Reserva</a></li>
                    <li><a href="#contact">Contacto</a></li>


                    <a class="btn-book-a-table" href="iniciosesion.php">Inicia sesión</a>
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
            <li>Modifica tu reserva</li>
          </ol>
        </div>

      </div>
    </div>
        <div class="container">
            <div class="section-header">
                <h2>Modificar Reserva</h2>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo $reserva['date']; ?>">
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Hora</label>
                    <input type="time" class="form-control" id="time" name="time"
                        value="<?php echo $reserva['time']; ?>">
                </div>
                <div class="mb-3">
                    <label for="people" class="form-label">Personas</label>
                    <input type="number" class="form-control" id="people" name="people"
                        value="<?php echo $reserva['people']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
            </form>
        </div>
    </main>

    <footer id="footer" class="footer">
        <!-- Pie de página de la página -->
    </footer>

    <!-- Scripts JavaScript -->
</body>

</html>
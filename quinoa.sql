-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-05-2024 a las 16:40:52
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `quinoa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `name`, `descrip`, `price`, `img`, `state`) VALUES
(1, 'Ensalada de Quinoa', 'Ensalada fresca con quinoa, tomate, pepino y aderezo de limón.', 10.99, 'img/food/ensalada_quinoa.png', 1),
(2, 'Curry de Vegetales', 'Curry vegetariano con una mezcla de vegetales frescos y arroz basmati.', 12.99, 'img/food/curry_vegetales.png', 1),
(3, 'Burger de Lentejas', 'Hamburguesa vegetariana a base de lentejas con guarnición de patatas fritas.', 8.99, 'img/food/burger_lentejas.png', 1),
(4, 'Sushi Vegano', 'Sushi roll vegano con aguacate, pepino y zanahoria.', 14.99, 'img/food/sushi_vegano.png', 1),
(5, 'Pizza de Verduras', 'Pizza vegetariana con una variedad de verduras frescas y queso vegano.', 11.99, 'img/food/pizza_verduras.png', 1),
(14, 'Prueba', 'Prueba', 1.00, 'img/food/', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserves`
--

CREATE TABLE `reserves` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `people` int(11) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserves`
--

INSERT INTO `reserves` (`id`, `name`, `mail`, `phone`, `date`, `time`, `people`, `msg`, `type`) VALUES
(1, 'Juan', 'juan@example.com', '123456789', '2024-05-15', '14:00 - 15:00', 4, 'Sin preferencias', 'Invitado'),
(2, 'María', 'maria@example.com', '987654321', '2024-05-16', '13:00 - 14:00', 2, 'Cerca de la ventana', 'Invitado'),
(4, 'Ana', 'ana@example.com', '333444555', '2024-05-18', '15:00 - 16:00', 8, 'Cumpleaños', 'Invitado'),
(5, 'Cliente1', 'cliente1@example.com', '333333333', '2024-05-12', '13:00 - 14:00', 2, 'Sin preferencias', 'Cliente'),
(6, 'Prueba 1', 'prueba@example.com', '12345678', '2024-05-22', '14:00 - 15:00', 4, '', 'Invitado'),
(7, 'Prueba', 'prueba@example.com', '123456789', '2024-05-09', '14:00 - 15:00', 8, '', 'Cliente'),
(11, 'María', 'maritaloli@gmail.com', '62355236', '2024-05-30', '14:00 - 15:00', 8, '', 'Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `sites` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tables`
--

INSERT INTO `tables` (`id`, `sites`) VALUES
(1, 4),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(7, 4),
(8, 4),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 8),
(22, 8),
(23, 8),
(26, 8),
(27, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `pass`, `mail`, `phone`, `type`) VALUES
(1, 'Admin', 'adminpass', 'admin@example.com', '999888777', 'Administrador'),
(2, 'Empleado1', 'emppass1', 'empleado1@example.com', '111111111', 'Empleado'),
(3, 'Empleado2', 'emppass2', 'empleado2@example.com', '222222222', 'Empleado'),
(4, 'Cliente1', 'clientepass1', 'cliente1@example.com', '333333333', 'Cliente'),
(6, 'Prueba', '12345', 'prueba@example.com', '123456789', 'Cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reserves`
--
ALTER TABLE `reserves`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `reserves`
--
ALTER TABLE `reserves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

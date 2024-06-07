-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-06-2024 a las 15:13:10
-- Versión del servidor: 10.11.7-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u145872577_quinoa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `name`, `descrip`, `category`, `price`, `img`, `state`) VALUES
(1, 'Ensalada de Quinoa', 'Ensalada fresca con quinoa, tomate, pepino y aderezo de limón.', 'Entrante', 10.99, 'img/food/ensalada_quinoa.png', 1),
(2, 'Curry de Vegetales', 'Curry vegetariano con una mezcla de vegetales frescos y arroz basmati.', 'Principal', 12.99, 'img/food/curry_vegetales.png', 1),
(3, 'Burger de Lentejas', 'Hamburguesa vegetariana a base de lentejas con guarnición de patatas fritas.', 'Principal', 8.99, 'img/food/burger_lentejas.png', 1),
(4, 'Sushi Vegano', 'Sushi roll vegano con aguacate, pepino y zanahoria.', 'Principal', 14.99, 'img/food/sushi_vegano.png', 1),
(5, 'Pizza de Verduras', 'Pizza vegetariana con una variedad de verduras frescas y queso vegano.', 'Principal', 11.99, 'img/food/pizza_verduras.png', 1),
(17, 'Nachos mexicanos', 'Nachos con guacamole, tomate, frijoles negros, tomate y jalapeño', 'Entrante', 6.00, 'img/food/mexican-nachos-tortilla-chips-with-black-beans-guacamole-tomato-jalapeno-isolated-white-background.png', 1),
(24, 'Paella de verduras', 'Arroz, verduras de temporada, azafrán y pimentón', 'Principal', 10.00, 'img/food/24_Paella-8V.png', 1),
(25, 'Espaguetis', 'Espaguetis con salsa boloñesa de soja y tomate, con opción de queso vegano', 'Principal', 8.00, 'img/food/25_spaghetti-with-bolognese-sauce-isolated-white-background.png', 1),
(26, 'Cheese cake', 'Cheese cake de arándanos', 'Postre', 4.00, 'img/food/blueberry-cheese-cake.jpg', 1),
(27, 'Cinnamon Roll', 'Rollo de canela', 'Postre', 3.00, 'img/food/27_cinnamon-roll.jpg', 1),
(28, 'Hummus', 'Hummus con crudités de zanahoria', 'Entrante', 3.00, 'img/food/28_foto-hummus-de-zanahoria-tamaño-sitio-web-540x458.jpg', 1),
(29, 'Cupcake', 'Cupcake de oreo', 'Postre', 3.00, 'img/food/cupcakes.jpg', 1),
(33, 'Tarta de chocolate', 'Tarta de chocolate con frambuesas', 'Postre', 4.50, 'img/food/33_slice-tasty-chocolate-cake-with-strawberry-top.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserves`
--

CREATE TABLE `reserves` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `people` int(11) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `table_num` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserves`
--

INSERT INTO `reserves` (`id`, `id_usuario`, `date`, `time`, `people`, `msg`, `type`, `table_num`) VALUES
(51, 41, '2024-06-09', '14:00 - 15:00', 5, '1 Persona celíaca', 'Cliente', 20),
(52, 43, '2024-06-10', '13:00 - 14:00', 2, '', 'Cliente', 23),
(53, 44, '2024-06-14', '14:00 - 15:00', 2, '', 'Cliente', 23),
(54, 45, '2024-06-15', '13:00 - 14:00', 8, '', 'Cliente', 12),
(55, NULL, '2024-06-13', '15:00 - 16:00', 3, '', 'Invitado', 17);

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
(4, 8),
(5, 4),
(6, 8),
(7, 4),
(8, 4),
(9, 8),
(10, 8),
(11, 4),
(12, 8),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(17, 4),
(20, 5),
(22, 6),
(23, 2);

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
  `type` varchar(20) DEFAULT NULL,
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `pass`, `mail`, `phone`, `type`, `state`) VALUES
(39, 'Administrador', '$2y$10$ymoQXMILXYfr5OwzMyKkQeGJoH4T/EzJGPGQLPk/aDg96VvJBI2dS', 'admin@example.com', '', 'Administrador', 1),
(40, 'Empleado1', '$2y$10$czhJ3eyEVl6BWAwWZt9Ax.Y7XJWeOhWuJB3pUmgdWA0uUuBFFtyzW', 'empleado1@example.com', '623668172', 'Empleado', 1),
(41, 'Cliente1', '$2y$10$lfpOr11D/ZJzy1CSNjmHGe7yte1RqXKIktKhLcM94fOmT6TPcL.q.', 'cliente1@example.com', '672829123', 'Cliente', 1),
(42, 'Cliente2', '$2y$10$Q7eHRJ.1uLOmquSU6U5pUe7pNFkIotY.MzAD/nIPKjj.a14fb0Ht2', 'cliente2@example.com', '628917202', 'Cliente', 0),
(43, 'Cliente3', '$2y$10$cBCOPX.nkO0InNQMKScTBuYTjLsdcsgzLRwLjEhHjKhMCK1OKv84q', 'cliente3@example.com', '627881923', 'Cliente', 1),
(44, 'James Lomax', '$2y$10$zVmgi3IpQSfrXlAtlSaFn.SZFtTFqHrsNHFQrtYojV1TnH5xg/9Rm', 'jameslomax@quinoa.com', '66600182', 'Cliente', 1),
(45, 'Cliente20', '$2y$10$8H7zIr6FIUwKnMnQ4HvukerHxDfMJow9r29NMm/2qSbYgawou.1o2', 'cliente20@gmail.com', '600202020', 'Empleado', 1);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_usuario` (`id_usuario`),
  ADD KEY `fk_table_num` (`table_num`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `reserves`
--
ALTER TABLE `reserves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reserves`
--
ALTER TABLE `reserves`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reserves_tables` FOREIGN KEY (`table_num`) REFERENCES `tables` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_table_num` FOREIGN KEY (`table_num`) REFERENCES `tables` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

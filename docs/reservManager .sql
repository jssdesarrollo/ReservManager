-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-01-2018 a las 00:53:53
-- Versión del servidor: 5.7.20-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservManager`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `empresaCIF` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `empresaEmail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `empresaTelefono` int(9) NOT NULL,
  `empresaDireccion` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `empresaNombreComercial` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Datos de empresas';

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`empresaCIF`, `empresaEmail`, `empresaTelefono`, `empresaDireccion`, `empresaNombreComercial`) VALUES
('00000000A', 'info@talleresgarcia.com', 976123123, 'Avda Madrid, 23 - Zaragoza - Zaragoza', 'Talleres garcía, SL'),
('00000001B', 'info@salonessol.com', 976124124, 'Camino de las Torres, 8', 'Salones Sol '),
('00000001C', 'info@clinicasvieira.com', 976125125, 'Independencia, 26', 'Clínicas Vieira');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineasWorkflow`
--

CREATE TABLE `lineasWorkflow` (
  `workflowID` int(5) NOT NULL,
  `workflowNombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `empresaCIF` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `workflowDescripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `workflowHoraInicio` time NOT NULL,
  `workflowHoraFin` time NOT NULL,
  `workflowDias` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Líneas de workflow de las empresas';

--
-- Volcado de datos para la tabla `lineasWorkflow`
--

INSERT INTO `lineasWorkflow` (`workflowID`, `workflowNombre`, `empresaCIF`, `workflowDescripcion`, `workflowHoraInicio`, `workflowHoraFin`, `workflowDias`) VALUES
(15, 'Línea 1 - Elevador para cambios de aceite', '00000000A', 'Elevador para cambios de aceite', '08:00:00', '14:00:00', 'lun-mar-mie-jue-vie--'),
(16, 'Linea 2 - Sustitución de neumáticos', '00000000A', 'Línea de sustitución de neumáticos', '08:00:00', '14:00:00', 'lun-mar-mie-jue-vie--'),
(17, 'Línea de revisión pre-ITV', '00000000A', 'Línea para revisiones pre-ITV y servicios varios', '08:00:00', '14:00:00', 'lun-mar-mie-jue-vie--');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `reservaID` int(5) NOT NULL,
  `reservaInicio` datetime NOT NULL,
  `reservaFin` datetime NOT NULL,
  `reservaObservaciones` text COLLATE utf8_unicode_ci NOT NULL,
  `userEmail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `workflowID` int(5) NOT NULL,
  `servicioID` int(5) NOT NULL,
  `reservaEstado` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `reservaObservacionesFinalizacion` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Datos de reservas';

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`reservaID`, `reservaInicio`, `reservaFin`, `reservaObservaciones`, `userEmail`, `workflowID`, `servicioID`, `reservaEstado`, `reservaObservacionesFinalizacion`) VALUES
(19, '2018-01-04 10:00:00', '2018-01-04 11:00:00', ' MICHELIN 255 55 16 V', 'franciscoperez@example.com', 16, 3, 'finalizada', 'Pago con tarjeta. Num operacion: 3542'),
(20, '2018-01-04 12:00:00', '2018-01-04 13:00:00', ' GOODYEAR 265 65 15', 'lauralopez@example.com', 16, 3, 'finalizada', 'Pago en efectivo. Ticket: 2653'),
(21, '2018-01-30 10:30:00', '2018-01-30 12:30:00', ' Sustituir escobillas limpia parabrisas delanteras', 'ramonsanchez@example.com', 17, 6, 'activa', ''),
(22, '2018-01-31 09:00:00', '2018-01-31 11:00:00', ' Revisión general', 'lauralopez@example.com', 17, 6, 'activa', ''),
(23, '2018-02-14 10:00:00', '2018-02-14 11:00:00', ' Aceite sintético 5 40 WD', 'ramonsanchez@example.com', 15, 9, 'activa', ''),
(24, '2018-01-15 11:11:00', '2018-01-15 12:41:00', ' Bridgestone ref: 2035', 'lauralopez@example.com', 16, 4, 'activa', ''),
(25, '2018-01-24 12:00:00', '2018-01-24 13:30:00', ' Marca blanca 235 65 17', 'ramonsanchez@example.com', 16, 4, 'activa', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `servicioID` int(5) NOT NULL,
  `servicioNombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `servicioDescripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `servicioDuracion` time NOT NULL,
  `empresaCIF` varchar(9) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Servicios creados por las empresas';

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`servicioID`, `servicioNombre`, `servicioDescripcion`, `servicioDuracion`, `empresaCIF`) VALUES
(3, 'Cambio neumáticos X2', 'Sustitución de juego de dos neumáticos en el mismo eje.', '01:00:00', '00000000A'),
(4, 'Cambio neumáticos X4', 'Sustitución de juego de 4 neumáticos', '01:30:00', '00000000A'),
(6, 'Revisión 20 puntos pre-ITV', 'Revisión en 20 puntos estratégicos de forma preventiva a la inspección ITV.', '02:00:00', '00000000A'),
(7, 'Cambio amortiguadores X2', 'Sustitución de juego de dos amortiguadores en el mismo eje.', '02:30:00', '00000000A'),
(8, 'Cambio amortiguadores X4', 'Sustitución de juego de 4 amortiguadores', '03:00:00', '00000000A'),
(9, 'Cambio de aceite y filtro', 'Sustitución de aceite motor y filtro de aceite', '01:00:00', '00000000A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serviciosWF`
--

CREATE TABLE `serviciosWF` (
  `empresaCIF` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `servicioID` int(5) NOT NULL,
  `workflowID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `serviciosWF`
--

INSERT INTO `serviciosWF` (`empresaCIF`, `servicioID`, `workflowID`) VALUES
('00000000A', 3, 16),
('00000000A', 4, 16),
('00000000A', 6, 17),
('00000000A', 7, 16),
('00000000A', 8, 16),
('00000000A', 9, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `userNombre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `userTelefono` int(9) NOT NULL,
  `userEmail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `userContrasena` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userRol` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `empresaCIF` varchar(9) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Datos de usuarios';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userNombre`, `userTelefono`, `userEmail`, `userContrasena`, `userRol`, `empresaCIF`) VALUES
('Francisco Pérez', 666111222, 'franciscoperez@example.com', '9c87400128d408cdcda0e4b3ff0e66fa', 'c', '00000000A'),
('Talleres garcía, SLAdmin', 976123123, 'info@talleresgarcia.com', '9c87400128d408cdcda0e4b3ff0e66fa', 'a', '00000000A'),
('Laura López', 666777888, 'lauralopez@example.com', '9c87400128d408cdcda0e4b3ff0e66fa', 'c', '00000000A'),
('Ramón Sánchez', 617123123, 'ramonsanchez@example.com', '9c87400128d408cdcda0e4b3ff0e66fa', 'c', '00000000A');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`empresaCIF`);

--
-- Indices de la tabla `lineasWorkflow`
--
ALTER TABLE `lineasWorkflow`
  ADD PRIMARY KEY (`workflowID`),
  ADD KEY `empresaCIF` (`empresaCIF`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`reservaID`),
  ADD KEY `servicioID` (`servicioID`),
  ADD KEY `userID` (`userEmail`),
  ADD KEY `workflowID` (`workflowID`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`servicioID`),
  ADD KEY `empresaCIF` (`empresaCIF`),
  ADD KEY `empresaCIF_2` (`empresaCIF`);

--
-- Indices de la tabla `serviciosWF`
--
ALTER TABLE `serviciosWF`
  ADD PRIMARY KEY (`servicioID`,`workflowID`),
  ADD KEY `workflowID` (`workflowID`),
  ADD KEY `empresaCIF` (`empresaCIF`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`userEmail`),
  ADD KEY `empresaCIF` (`empresaCIF`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `lineasWorkflow`
--
ALTER TABLE `lineasWorkflow`
  MODIFY `workflowID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `reservaID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `servicioID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lineasWorkflow`
--
ALTER TABLE `lineasWorkflow`
  ADD CONSTRAINT `lineasWorkflow_ibfk_1` FOREIGN KEY (`empresaCIF`) REFERENCES `empresas` (`empresaCIF`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`servicioID`) REFERENCES `servicios` (`servicioID`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`workflowID`) REFERENCES `lineasWorkflow` (`workflowID`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`userEmail`) REFERENCES `usuarios` (`userEmail`);

--
-- Filtros para la tabla `serviciosWF`
--
ALTER TABLE `serviciosWF`
  ADD CONSTRAINT `serviciosWF_ibfk_1` FOREIGN KEY (`servicioID`) REFERENCES `servicios` (`servicioID`),
  ADD CONSTRAINT `serviciosWF_ibfk_2` FOREIGN KEY (`workflowID`) REFERENCES `lineasWorkflow` (`workflowID`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empresaCIF`) REFERENCES `empresas` (`empresaCIF`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

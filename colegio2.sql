-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2025 a las 04:12:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `colegio2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `padre` varchar(100) DEFAULT NULL,
  `madre` varchar(100) DEFAULT NULL,
  `apoderado` varchar(100) DEFAULT NULL,
  `celular_apoderado` varchar(20) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `nota_ponderada` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `tipo_documento`, `dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `direccion`, `padre`, `madre`, `apoderado`, `celular_apoderado`, `fecha_ingreso`, `grupo_id`, `nota_ponderada`) VALUES
(1, 'DNI', '12312312', 'Juan', 'Pérez', 'López', '2013-05-10', 'Av. Perú 123', 'Carlos Pérez', 'Ana López', 'Carlos Pérez', '987654321', '2024-03-01', 1, 16.50),
(2, 'DNI', '45645645', 'Luis', 'Ramírez', 'Torres', '2012-06-15', 'Av. Lima 321', 'Pedro Ramírez', 'Sofía Torres', 'Pedro Ramírez', '912345678', '2024-03-01', 1, 14.80),
(3, 'DNI', '78978978', 'Andrea', 'Quispe', 'Mamani', '2014-02-20', 'Jr. Puno 567', 'Mario Quispe', 'María Mamani', 'María Quispe', '945612378', '2024-03-01', 2, 17.90),
(4, 'DNI', '72322732', 'Pedro Juan Luis', 'Guevara ', 'Alvarado', '2003-03-08', 'Mantaro 555', 'Luis Enrique Guevara Miranda', 'Alina Victoria Alvarado Leiva', 'Alina Victoria Alvarado Leiva', '923305711', '2025-12-09', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anios`
--

CREATE TABLE `anios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `anios`
--

INSERT INTO `anios` (`id`, `nombre`) VALUES
(1, '2023'),
(2, '2024'),
(3, '2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`) VALUES
(1, 'Matemática'),
(2, 'Comunicación'),
(3, 'Ciencia y Ambiente'),
(4, 'Personal Social'),
(5, 'Educación Física');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` enum('ASISTIO','FALTO','TARDE') DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `alumno_id`, `fecha`, `estado`, `observacion`) VALUES
(1, 1, '2024-04-01', 'ASISTIO', ''),
(2, 1, '2024-04-02', 'FALTO', 'Enfermo'),
(3, 2, '2024-04-01', 'TARDE', ''),
(4, 3, '2024-04-01', 'ASISTIO', ''),
(8, 4, '2025-12-09', 'TARDE', NULL),
(9, 1, '2025-12-09', 'ASISTIO', NULL),
(10, 2, '2025-12-09', 'TARDE', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(120) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `anio_ingreso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `usuario_id`, `nombres`, `apellido_paterno`, `apellido_materno`, `documento`, `telefono`, `direccion`, `foto`, `anio_ingreso`) VALUES
(1, 2, 'Pancho', 'Villa', '', '12345678', '987654321', 'Av. Lima 123', NULL, 2021),
(2, 3, 'Rosa', 'Gutiérrez', '', '87654321', '987111222', 'Jr. Cusco 456', NULL, 2022);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_areas`
--

CREATE TABLE `docentes_areas` (
  `id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docentes_areas`
--

INSERT INTO `docentes_areas` (`id`, `docente_id`, `area_id`, `grupo_id`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 2, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id`, `nombre`) VALUES
(1, 'Primero'),
(2, 'Segundo'),
(3, 'Tercero'),
(4, 'Cuarto'),
(5, 'Quinto'),
(6, 'Sexto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `grado_id` int(11) NOT NULL,
  `seccion_id` int(11) NOT NULL,
  `anio_id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `grado_id`, `seccion_id`, `anio_id`, `docente_id`) VALUES
(1, 6, 1, 2, 1),
(2, 5, 2, 2, 2),
(3, 3, 2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `dia` varchar(15) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `grupo_id`, `dia`, `hora_inicio`, `hora_fin`, `area_id`) VALUES
(1, 1, 'Lunes', '08:00:00', '09:00:00', 1),
(2, 1, 'Martes', '09:00:00', '10:00:00', 2),
(3, 2, 'Lunes', '08:00:00', '09:00:00', 3),
(4, 2, 'Viernes', '10:00:00', '11:00:00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `bimestre` enum('1','2','3','4') DEFAULT NULL,
  `docente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id`, `alumno_id`, `area_id`, `nota`, `bimestre`, `docente_id`) VALUES
(1, 1, 1, 17.50, '1', 1),
(2, 2, 1, 14.00, '1', 1),
(3, 3, 3, 18.20, '1', 2),
(4, 4, 5, 20.00, '1', 1),
(5, 1, 5, 15.00, '1', 1),
(6, 2, 5, 15.00, '1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padres_alumnos`
--

CREATE TABLE `padres_alumnos` (
  `id` int(11) NOT NULL,
  `padre_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `padres_alumnos`
--

INSERT INTO `padres_alumnos` (`id`, `padre_id`, `alumno_id`) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id`, `nombre`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('ADMIN','DOCENTE','PADRE') NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `dni`, `correo`, `usuario`, `password`, `rol`, `estado`, `fecha_creacion`) VALUES
(1, 'Admin', 'Principal', '00000000', 'admin@colegio.com', 'admin', '$2y$10$9j9H.lYA6kyXCOfFCOboQOUaobtxP9AixQHRlNsCr8yhuCvOPak4.', 'ADMIN', 'ACTIVO', '2025-12-09 21:02:27'),
(2, 'Pancho', 'Villa', '12345678', 'pvilla@colegio.com', 'doc1', '$2y$10$9j9H.lYA6kyXCOfFCOboQOUaobtxP9AixQHRlNsCr8yhuCvOPak4.', 'DOCENTE', 'ACTIVO', '2025-12-09 21:02:27'),
(3, 'Rosa', 'Gutiérrez', '87654321', 'rgutierrez@colegio.com', 'doc2', '$2y$10$9j9H.lYA6kyXCOfFCOboQOUaobtxP9AixQHRlNsCr8yhuCvOPak4.', 'DOCENTE', 'ACTIVO', '2025-12-09 21:02:27'),
(4, 'Carlos', 'Pérez', '45678912', 'cperez@gmail.com', 'padre1', '$2y$10$9j9H.lYA6kyXCOfFCOboQOUaobtxP9AixQHRlNsCr8yhuCvOPak4.', 'PADRE', 'ACTIVO', '2025-12-09 21:02:27'),
(5, 'María', 'Quispe', '78945612', 'mquispe@gmail.com', 'padre2', '$2y$10$9j9H.lYA6kyXCOfFCOboQOUaobtxP9AixQHRlNsCr8yhuCvOPak4.', 'PADRE', 'ACTIVO', '2025-12-09 21:02:27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indices de la tabla `anios`
--
ALTER TABLE `anios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `docentes_areas`
--
ALTER TABLE `docentes_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `docente_id` (`docente_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grado_id` (`grado_id`),
  ADD KEY `seccion_id` (`seccion_id`),
  ADD KEY `anio_id` (`anio_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `area_id` (`area_id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `padres_alumnos`
--
ALTER TABLE `padres_alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `padre_id` (`padre_id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `anios`
--
ALTER TABLE `anios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `docentes_areas`
--
ALTER TABLE `docentes_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `padres_alumnos`
--
ALTER TABLE `padres_alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `docentes_areas`
--
ALTER TABLE `docentes_areas`
  ADD CONSTRAINT `docentes_areas_ibfk_1` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`),
  ADD CONSTRAINT `docentes_areas_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `docentes_areas_ibfk_3` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`),
  ADD CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`),
  ADD CONSTRAINT `grupos_ibfk_3` FOREIGN KEY (`anio_id`) REFERENCES `anios` (`id`),
  ADD CONSTRAINT `grupos_ibfk_4` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`);

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `padres_alumnos`
--
ALTER TABLE `padres_alumnos`
  ADD CONSTRAINT `padres_alumnos_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `padres_alumnos_ibfk_2` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

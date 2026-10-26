-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2026 a las 23:36:54
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
-- Base de datos: `saeMELC`
--

-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS saeMELC

    CHARACTER SET utf8mb4

    COLLATE utf8mb4_spanish_ci;

USE saeMELC;
--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idalumn` int(11) NOT NULL,
  `matricula_al` varchar(50) NOT NULL,
  `nombre_al` varchar(100) NOT NULL,
  `apaterno_al` varchar(100) NOT NULL,
  `amaterno_al` varchar(100) NOT NULL,
  `mail_al` varchar(150) NOT NULL,
  `estatus_al` varchar(10) DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`idalumn`, `matricula_al`, `nombre_al`, `apaterno_al`, `amaterno_al`, `mail_al`, `estatus_al`) VALUES
(1, '6666', 'Ashley', 'Graves', 'G', 'hermanoslocos@gmail.com', 'ALTA'),
(3, '6667', 'Andrew', 'Graves', 'G', 'hermanoslocos@gmail.com', 'ALTA'),
(4, '1234', 'Lilith', 'Fuentes', 'Perez', 'algunalumna@gmail.com', 'ALTA'),
(5, '0000', 'Prueba', 'Reprobado', 'jiji', 'todonoob@gmail.com', 'BAJA'),
(8, '1247', 'Lynette', 'Scavo', '.', 'Scavo@gmail.com', 'ALTA'),
(9, 'Cortes', 'Martin', 'Emmanuel', 'Lopez', 'algunalumno@gmail.com', 'ALTA'),
(10, '9238', 'Rocio', 'Cornejo', 'Esquivel', 'Rox@gmail.com', 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `idasignatura` int(11) NOT NULL,
  `idmat` int(11) NOT NULL,
  `idalumn` int(11) NOT NULL,
  `idprof` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaciones`
--

INSERT INTO `asignaciones` (`idasignatura`, `idmat`, `idalumn`, `idprof`) VALUES
(2, 1, 1, 2),
(3, 2, 3, 1),
(4, 2, 1, 2),
(6, 4, 10, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calif` int(11) NOT NULL,
  `idalumn` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `calificacion` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id_calif`, `idalumn`, `id_unidad`, `calificacion`) VALUES
(1, 1, 1, 10.00),
(2, 3, 1, 9.00),
(3, 4, 1, 10.00),
(4, 1, 2, 9.00),
(5, 3, 2, 9.00),
(6, 4, 2, 8.00),
(7, 1, 10, 9.00),
(8, 3, 10, 9.00),
(9, 4, 10, 9.00),
(10, 1, 1, 9.00),
(11, 8, 1, 9.00),
(12, 9, 1, 8.00),
(13, 10, 1, 10.00),
(14, 8, 2, 9.00),
(15, 9, 2, 9.00),
(16, 10, 2, 10.00),
(17, 8, 10, 9.00),
(18, 9, 10, 9.00),
(19, 10, 10, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `idgrupo` int(11) NOT NULL,
  `clave_grupo` varchar(50) NOT NULL,
  `nombre_grupo` varchar(100) NOT NULL,
  `estatus_grupo` varchar(10) DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`idgrupo`, `clave_grupo`, `nombre_grupo`, `estatus_grupo`) VALUES
(1, '406', '4° Semestre Tecnologías de la Información', 'ALTA'),
(2, '101', '1° Semestre Tecnologías de la Información', 'ALTA'),
(3, '407', '4° Semestre Tecnologías de la Información.', 'ALTA'),
(4, '202', '2° Semestre Tecnologías de la Información', 'ALTA'),
(5, '501', '5° Semestre Tecnologías de la Información', 'ALTA'),
(6, '601', '6° Semestre Tecnologías de la Información', 'ALTA'),
(7, '501', '5° Semestre Tecnologías de la Información', 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_alumnos`
--

CREATE TABLE `grupo_alumnos` (
  `id_ga` int(11) NOT NULL,
  `idgrupo` int(11) NOT NULL,
  `idalumn` int(11) NOT NULL,
  `estatus` varchar(10) DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo_alumnos`
--

INSERT INTO `grupo_alumnos` (`id_ga`, `idgrupo`, `idalumn`, `estatus`) VALUES
(1, 1, 1, 'ALTA'),
(2, 1, 3, 'ALTA'),
(3, 1, 10, 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `idmat` int(11) NOT NULL,
  `clave_mat` varchar(50) NOT NULL,
  `nombre_mat` varchar(150) NOT NULL,
  `creditos` int(11) NOT NULL,
  `estatus_mat` varchar(10) DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`idmat`, `clave_mat`, `nombre_mat`, `creditos`, `estatus_mat`) VALUES
(1, 'MAT01', 'Matematicas Discretas', 4, 'ALTA'),
(2, 'PROG02', 'Programacion II', 5, 'ALTA'),
(3, 'Filo01', 'Filosofia', 1, 'BAJA'),
(4, 'FILO02', 'Filosofia II', 2, 'ALTA'),
(5, 'DB01', 'Base de Datos', 5, 'ALTA'),
(6, 'rrr34', '', 4, 'BAJA'),
(7, 'MAT07', 'Matematicas para la toma de Decisiones', 2, 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `idprof` int(11) NOT NULL,
  `nombre_prof` varchar(100) NOT NULL,
  `apaterno_prof` varchar(100) NOT NULL,
  `amaterno_prof` varchar(100) NOT NULL,
  `mail_prof` varchar(150) NOT NULL,
  `estatus_prof` varchar(10) DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`idprof`, `nombre_prof`, `apaterno_prof`, `amaterno_prof`, `mail_prof`, `estatus_prof`) VALUES
(0, 'Robet', 'Prueba', 'Pablo', 'algunprofebaja@gmail.com', 'ALTA'),
(1, 'Eva Sara', 'García', ' Hernandez', 'DocenteSara@gmail.com', 'ALTA'),
(2, 'Chris', 'Prince', 'NEO', 'algunprofeNEO@gmail.com', 'ALTA'),
(3, 'Carlos', 'Mendoza', 'Ramirez', 'algunprofe@gmail.com', 'ALTA'),
(4, 'Anri', 'Teieri', 'Teieri', 'Teieri@gmail.com', 'ALTA'),
(5, 'Leonardo', 'Luna', 'NEO', 'algunprofeNEO2@gmail.com', 'ALTA'),
(6, 'Gabrielle', 'Solis', '.', 'Solis@gmail.com', 'ALTA');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idalumn`);

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`idasignatura`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calif`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`idgrupo`);

--
-- Indices de la tabla `grupo_alumnos`
--
ALTER TABLE `grupo_alumnos`
  ADD PRIMARY KEY (`id_ga`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idmat`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`idprof`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idalumn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `idasignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `idgrupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `grupo_alumnos`
--
ALTER TABLE `grupo_alumnos`
  MODIFY `id_ga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `idmat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
